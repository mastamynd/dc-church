<?php

namespace Modules\PaymentReception\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\Document\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Church\Models\Church;
use Modules\Church\Models\PaymentDivision;
use Modules\PaymentReception\Export\PaymentReceptionExport;
use Modules\PaymentReception\Models\PaymentReception;
use Modules\PaymentReception\Models\PaymentReceptionItems;
use Modules\PaymentReception\Services\PaymentPoster;

class Main extends Controller
{
	public function paymentIndex(){
		if(request()->has('remove_duplicates')){
			return $this->remove_duplicates();
		}

		$prefix = DB::getTablePrefix();
		$items = $prefix . 'payment_reception_items';
		$receptions = $prefix . 'payment_receptions';

		$payments = PaymentReception::query()
			->with('church:id,name,code')
			->select('payment_receptions.*')
			->selectRaw('(SELECT COALESCE(SUM(amount), 0) FROM '.$items.' WHERE payment_reception_id = '.$receptions.'.id AND deleted_at IS NULL) as amount')
			->selectRaw('(SELECT month FROM '.$items.' WHERE payment_reception_id = '.$receptions.'.id AND deleted_at IS NULL ORDER BY id ASC LIMIT 1) as month')
			->selectRaw('(SELECT week FROM '.$items.' WHERE payment_reception_id = '.$receptions.'.id AND deleted_at IS NULL ORDER BY id ASC LIMIT 1) as week')
			->orderByDesc('payment_receptions.created_at')
			->collect('created_at');

		$collection = method_exists($payments, 'getCollection') ? $payments->getCollection() : $payments;
		$collection->each->setAppends([]);

		return $this->response('payment-reception::index', compact('payments'));
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function paymentShow($id='create')
	{
		$payment_modes = ['mpesa'=>'M-Pesa', 'cash'=>'Cash', 'bank deposit'=>'Bank Deposit', 'ussd'=>'USSD', 'portal'=>'Portal'];
		$start_year = 2018;
		$paymentYear = date('Y');
		$paymentMonth = date('M');
		$paymentWeek = \Carbon\Carbon::now()->weekOfMonth;
		$weeks = [1,2,3,4,5];
		$months = [
			'long' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
			'short' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec']
		];

		$paymentReception = is_numeric($id) ? PaymentReception::with('items')->find($id) : null;

		$churches = Church::query()->forPicker();

		if(isset($paymentReception->id)){
			$churches = $churches->where('churches.id', $paymentReception->church_id);
		}

		$churches = $churches->limit(10)->get();

		$recentIds = PaymentReception::query()
			->when(isset($paymentReception->id), function ($query) use ($paymentReception) {
				$query->where('id', '!=', $paymentReception->id);
			})
			->orderByDesc('id')
			->limit(10)
			->pluck('id');

		$prefix = DB::getTablePrefix();

		$lastReceiptions = $recentIds->isEmpty()
			? collect()
			: PaymentReception::query()
				->join('churches as c', 'c.id', 'payment_receptions.church_id')
				->leftJoin('payment_reception_items as pri', function ($join) {
					$join->on('pri.payment_reception_id', 'payment_receptions.id')
						->whereNull('pri.deleted_at');
				})
				->whereIn('payment_receptions.id', $recentIds)
				->selectRaw($prefix.'c.name, '.$prefix.'c.code, '.$prefix.'payment_receptions.id, MAX('.$prefix.'pri.month) as month, MAX('.$prefix.'pri.year) as year, payer_name, COALESCE(SUM('.$prefix.'pri.amount), 0) as amount')
				->groupBy('payment_receptions.id', 'c.name', 'c.code', 'payment_receptions.payer_name')
				->orderByDesc('payment_receptions.id')
				->get();

		return $this->response('payment-reception::edit', compact('lastReceiptions', 'paymentReception', 'start_year', 'churches', 'payment_modes', 'months', 'weeks', 'paymentYear', 'paymentMonth', 'paymentWeek'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request, $id=0)
	{
		$dataArr = $request->all();
		$rows = $dataArr['data'];
		
		unset($dataArr['_token']);
		unset($dataArr['data']);

		$payment = intval($id) > 0 ? PaymentReception::find($id) : PaymentReception::create($dataArr);

		$previousIds = PaymentReception::query()
			->where('church_id', $dataArr['church_id'])
			->where('id', '!=', $payment->id)
			->pluck('id');

		if ($previousIds->isNotEmpty() && !empty($rows)) {
			$existsBefore = PaymentReceptionItems::query()
				->whereIn('payment_reception_id', $previousIds)
				->where(function ($query) use ($rows) {
					foreach ($rows as $row) {
						$query->orWhere(function ($period) use ($row) {
							$period->where('month', $row['month'])->where('year', $row['year']);

							if (isset($row['week']) && trim((string) $row['week']) !== '') {
								$period->where('week', $row['week']);
							}
						});
					}
				})
				->exists();

			if ($existsBefore) {
				flash('Payment for similar period exists')->important()->error();
				return redirect()->route('payment-reception.index');
			}
		}

		foreach($rows as $k=>$row){
			$row = (array) $row;
			$row['payment_reception_id'] = $payment->id;

			if(intval($id) > 0){
				$pR = PaymentReceptionItems::find($k);
				$pR->update($row);
			}
			else{
				PaymentReceptionItems::create($row);
			}
			
		}

		return redirect()->route('payment-reception.index');
	}

	public function post_multiple_payments(Request $request){
		$result = app(PaymentPoster::class)->postMany((array) $request->input('ids', []));

		$flash = flash($result['message']);
		if ($result['level'] === 'success') {
			$flash->success();
		} else {
			$flash->important()->error();
		}

		return redirect()->route('payment-reception.index');
	}

	public function post_payment($id, $internal = false){
		return app(PaymentPoster::class)->postOne($id, $internal);
	}

	public function create_invoice($payment){
		return app(PaymentPoster::class)->createInvoice($payment);
	}

	public function destroy($id){
		$payment = PaymentReception::find($id);
		if($payment->status == "Completed"){
			flash('The Tithe has already been posted! Cannot Delete!!')->error()->important();
			return redirect()->back();
		}
		
		$document = Document::where('parent_id', $payment->id)->first();
		PaymentDivision::where('invoice_id', $document->id)->delete();

		$document->delete();
		$payment->delete();
		return redirect()->back();
	}

	public function reverse($id){
		$payment = PaymentReception::find($id);
		$document = Document::where('parent_id', $payment->id)->first();

		PaymentDivision::where('invoice_id', $document->id)->delete();

		$document->delete();

		$payment->status = "Draft";
		$payment->save();

		flash('The posting was reversed!')->error()->important();
		return redirect()->back();
	}

	public function workflow(){
		return $this->response('payment-reception::payment-workflow');
	}

	public function export_payment_form(Request $request){
		$regionalData = PaymentDivision::with('region')->whereNull('payment_date')->whereNull('deleted_at')->whereIn('type', ['region']);		
		$subRegionalData = PaymentDivision::with('region','subregion')->whereNull('payment_date')->whereNull('deleted_at')->whereIn('type', ['sub_region']);
		$pastorsFundData = PaymentDivision::with('subregion')->whereNull('payment_date')->whereNull('deleted_at')->whereIn('type', ['pastors_fund']);
		$DCSFundData = PaymentDivision::with('subregion')->whereNull('payment_date')->whereNull('deleted_at')->whereIn('type', ['dc_secretariate']);
		$CTHFundData = PaymentDivision::with('subregion')->whereNull('payment_date')->whereNull('deleted_at')->whereIn('type', ['contribution_to_hq']);
		
		if($request->has('region')){
			$hasTitle = true;
			$regionalData = $regionalData->where('regional_id', $request->region);
			$subRegionalData = $subRegionalData->where('regional_id', $request->region);
			$pastorsFundData = $pastorsFundData->where('regional_id', $request->region);
			$DCSFundData = $DCSFundData->where('regional_id', $request->region);
			$CTHFundData = $CTHFundData->where('regional_id', $request->region);
		}

		if($request->has('month')){
			$regionalData = $regionalData->where('month', $request->month);
			$subRegionalData = $subRegionalData->where('month', $request->month);
			$pastorsFundData = $pastorsFundData->where('month', $request->month);
			$DCSFundData = $DCSFundData->where('month', $request->month);
			$CTHFundData = $CTHFundData->where('month', $request->month);
		}

		if($request->has('year')){
			$regionalData = $regionalData->where('year', $request->year);
			$subRegionalData = $subRegionalData->where('year', $request->year);
			$pastorsFundData = $pastorsFundData->where('year', $request->year);
			$DCSFundData = $DCSFundData->where('year', $request->year);
			$CTHFundData = $CTHFundData->where('year', $request->year);
		}

		$regionalData = $regionalData->selectRaw('regional_id, SUM(amount) as amount, type')->groupBy('regional_id')->get();
		$subRegionalData = $subRegionalData->selectRaw('regional_id, sub_regional_id, SUM(amount) as amount, type')->groupBy('sub_regional_id')->orderBy('regional_id')->get();
		$pastorsFundData = $pastorsFundData->selectRaw('regional_id, contact_id, SUM(amount+topup) as amount, type')->first();
		$DCSFundData = $DCSFundData->selectRaw('regional_id, contact_id, SUM(amount) as amount, type')->first();
		$CTHFundData = $CTHFundData->selectRaw('regional_id, contact_id, SUM(amount) as amount, type')->first();

		// return response()->json($subRegionalData);

		$demarcations = ["regions"=>[], 'sub_regions'=>[]];

		$existingRegions = [];

		foreach($regionalData as $rd){
			$existingRegions[] = $rd->regional_id;
			$demarcations["regions"][] = $rd;
		}

		$orphanSubs = [];

		foreach($subRegionalData as $rd){
			if(!in_array($rd->regional_id, $existingRegions)){
				if(!isset($orphanSubs[$rd->region->name])){
					$orphanSubs[$rd->region->name] = [];
				}
				$orphanSubs[$rd->region->name][] = $rd; 
			}
			else{
				if(!isset($demarcations['sub_regions'][$rd->regional_id])){
					$demarcations['sub_regions'][$rd->regional_id] = [];
				}
				$demarcations['sub_regions'][$rd->regional_id][] = $rd;
			}
		}

		$demarcations['orphan_subs'] = $orphanSubs;

		$demarcations['pastors_fund'] = $pastorsFundData;
		$demarcations['dc_secretariate'] = $DCSFundData;
		$demarcations['contribution_to_hq'] = $CTHFundData;

		// return response()->json($demarcations);
		

		$title = isset($hasTitle) ? $regionalData[0]->region->name." Payments" : "All Payments";

		return Excel::download(new PaymentReceptionExport($demarcations, $title), 'payment-list.xlsx');;
	}

	public function remove_duplicates(){
		$payment_reception = PaymentReception::whereNull('deleted_at')->select('id')->pluck('id');
		$invoices = Document::whereIn('parent_id', $payment_reception)->selectRaw('count(id) as duplicates, parent_id, GROUP_CONCAT(id) as invoices_id')->groupBy('parent_id')->get();
		$hasBeenTraversed = [];
		$toDeleteInvoice = [];
		foreach($invoices as $inv){
			$invoice_ids = explode(',', $inv->invoices_id);
			foreach($invoice_ids as $id){
				if(!in_array($inv->parent_id, $hasBeenTraversed)){
					$hasBeenTraversed[] = $inv->parent_id;
				}
				else{
					$toDeleteInvoice[] = $id;
				}
			}
		}

		Document::whereIn('id', $toDeleteInvoice)->delete();
		PaymentDivision::whereIn('invoice_id', $toDeleteInvoice)->delete();

		return json_encode([$toDeleteInvoice, $invoices]);
	}

	public function sortIssuePending(){
		$receptions = PaymentReception::where('status', 'pending_issue')->get();
		foreach($receptions as $reception){
			$hasInvoice = Document::where('parent_id', $reception->id)->count();

			if($hasInvoice > 0){
				$reception->status = "Completed";
				$reception->save();
			}
		}
	}
}
