<?php

namespace Modules\PaymentReception\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Document\Document;
use App\Models\Document\DocumentItem;
use App\Models\Document\DocumentTotal;
use Carbon\Carbon;
use Modules\Demarcations\Models\Demarcation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Church\Models\Church;
use Modules\Church\Models\PaymentDivision;
use Modules\PaymentReception\Export\PaymentReceptionExport;
use Modules\PaymentReception\Models\PaymentReception;
use Modules\PaymentReception\Models\PaymentReceptionItems;

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
		foreach($request->ids as $id){
			$this->post_payment($id, true);
		}

		return redirect()->route('payment-reception.index');
	}

	public function post_payment($id, $internal = false){
		$payment = PaymentReception::find($id);

		$church = Church::find($payment->church_id);

		if($payment->total <=0){
			$payment->status = 'pending_issue';
			$payment->save();
			flash('Payment for '.$church->name.' has no value!')->important()->error();
			return redirect()->route('payment-reception.view-tithe', $id);
		}
		
		if(!isset($church->subregion->church_id)){
			$payment->status = 'pending_issue';
			$payment->save();
			flash('No Sub-Region has been assigned to the church '.$church->name.'!')->important()->error();
			return redirect()->route('payment-reception.view-tithe', $id);
		}	

		if($church->pastors->count() == 0){
			$payment->status = 'pending_issue';
			$payment->save();
			flash('No Pastors add to the church '.$church->name.'!')->important()->error();
			return redirect()->route('payment-reception.view-tithe', $id);
		}	

		foreach($church->pastors as $pastor){
			$payment->status = 'pending_issue';
			$payment->save();
			if(in_array($pastor->rank, ['Select Rank...'])){
				flash('Pastor`s rank not set in church '.$church->name.'!')->important()->error();
				return redirect()->route('payment-reception.view-tithe', $id);
			}
		}

		if($church->senior_pastor->count() == 1){
			$payment->status = 'pending_issue';
			$payment->save();
			flash('Church '.$church->name.' missing Senior Pastor Details!')->important()->error();
			return redirect()->route('payment-reception.view-tithe', $id);
		}

		if($church->senior_pastor->count() > 1){
			$payment->status = 'pending_issue';
			$payment->save();
			flash('Church '.$church->name.' more than 1 Senior Pastor configured!')->important()->error();
			return redirect()->route('payment-reception.view-tithe', $id);
		}

		$hasInvoice = Document::where('parent_id', $payment->id)->count();

		if($payment->status == "Completed" || $hasInvoice > 0){
			flash('Tithe has already been submitted!')->important()->error();
			$payment->status = 'Completed';
			$payment->save();
			return redirect()->route('payment-reception.view-tithe', $id);
		}

		$this->create_invoice($payment);

		$payment->status = 'Completed';
		$payment->save();

		if($internal){
			return true;
		}

		flash('Tithe Submission Approved!')->success();

		return redirect()->route('payment-reception.view-tithe', $id);
	}

	public function create_invoice($payment){	
		$church = Church::find($payment->church_id);
		$ITEMS = $payment->items;
		$invoice_items = setting('church.payment_divisions');
		$invoice_items = json_decode($invoice_items, true);
		$theCONTACT = Contact::customer()->where('email', 'payment-reception@deliverancechurch.international')->first();

		// return response()->json($ITEMS, 200);

		foreach($ITEMS as $itm){
			$CNNxtID = setting('invoice.number_next');
			$DocNo = 'INV-'.str_pad($CNNxtID,5,"0",STR_PAD_LEFT);
			//The amount from the weekly distribution
			$INVTOTAL = floatval($itm->amount);
			$sub_total = 0;

			$newData = [
				'contact_id' => $theCONTACT->id,
				'company_id' => company_id(),
				'currency_code' => 'KES',
				'currency_rate' => 1,
				'contact_country' => 'KE',
				'contact_name' => 'System Portal',
				'contact_email' => $theCONTACT->email,
				'parent_id' => $payment->id,
				'status' => 'draft',
				'type' => 'invoice',
				'document_number' => $DocNo,
				'created_by' => auth()->user()->id,
				'created_at' => Carbon::now(),
				'issued_at' => Carbon::now(),
				'due_at' => Carbon::now(),
				'updated_at' => Carbon::now(),
				'amount' => 0
			];

			$CN = Document::create($newData);

			$CNNxtID += 1;

			setting(['invoice.number_next'=> $CNNxtID])->save();

			$splitCriteria = [
				1 => ['Senior'=>100],
				2 => ['Senior'=>60, 'Assistant'=>40]
			];
			
			foreach($invoice_items as $rowD){
				$distributedAmount = $INVTOTAL*(floatval($rowD['percentage'])/100);
				$sub_region = isset($church->subregion) ? $church->subregion->demarcation_id : 0;

				$subregDem = Demarcation::find($sub_region);

				$regionID = $subregDem->parent->parent_id ?? 0;

				if($rowD['code'] == 'pastors_fund'){
					$theTopUp = floatval($itm->topup ?? 0);
					$pastors = $church->pastors ?? [];
					$noCount = count($pastors);

					$split = $splitCriteria[$noCount] ?? ['Senior'=>40, 'Assistant'=>60];

					$splitAmounts = [];
					$topUpAmounts = [];

					foreach($split as $s=>$p){
						$splitAmounts[$s] = $p/100*$distributedAmount;
						if($theTopUp != 0){
							$topUpAmounts[$s] = $p/100*$theTopUp;
						}
						else{
							$topUpAmounts[$s] = 0;
						}
					}

					// return $topUpAmounts;

					foreach($pastors as $pastor){
						$PASTOR = $pastor->pastor;
						$pastorItemName = $pastor->position.' - '.$PASTOR->contact->name;
						$pastorDescription = 'pastor-'.$pastor->pastor_id;
						$pastorItem = Item::where('sku', $pastorDescription)->first();

						if(!isset($pastorItem->id)){
							$pastorItem = Item::create([
								'company_id' => company_id(),
								'name' => $pastorItemName,
								'sku' => $pastorDescription,
								'description' => $pastorItemName,
								'quantity' => 1,
								'sale_price' => 0,
								'purchase_price' => 0,
								'created_from' => 'core::ui', 
								'created_by' => user()->id
							]);
						}

						$paymentBracketAmount = $splitAmounts[$pastor->rank];
						
						$pTopUpBracketAmount = $topUpAmounts[$pastor->rank];
						if($noCount == 1){
							$price = $distributedAmount;
							$pTopUp = $theTopUp;
						}
						else
						{
							if($pastor->rank == 'Senior'){
								$price = $paymentBracketAmount; 
								$pTopUp = $pTopUpBracketAmount;
							}
							else{
								$otherRanks = $noCount-1;
								$price = $paymentBracketAmount/$otherRanks;
								$pTopUp = $pTopUpBracketAmount/$otherRanks;
							}
						}

						$itemArrayData = [
							'company_id' => company_id(),
							'item_id' => $pastorItem->id,
							'name' => $pastorItem->name,
							'description' => $pastorItem->description,
							'type' => 'invoice',
							'price' => $price,		
							'topup' => $pTopUp,
							'total' => $price,	
							'quantity' => 1,
							'document_id' => $CN->id,
							'updated_at' => Carbon::now(),
							'created_by' => auth()->user()->id,
							'created_at' => Carbon::now()
						];
						$newDocItem = DocumentItem::create($itemArrayData);

						$paymentDivision = PaymentDivision::create([
							'church_id' => $payment->church_id,
							'regional_id' => $regionID,
							'sub_regional_id' => $sub_region,
							'contact_id' => $pastor->pastor_id,
							'type' => $rowD['code'],
							'amount' => $price,
							'topup' => $pTopUp,
							'invoice_id' => $CN->id,
							'invoice_item_id' => $newDocItem->id,
							'year' => $itm->year,
							'month' => $itm->month
						]);

						$paymentDivision[$itm->month] = $price;
						$paymentDivision->save();

						$sub_total += floatval($itemArrayData['total']);
					}
				}
				else{
					$distributionItem = Item::where('sku', $rowD['code'])->first();

					if(!isset($distributionItem->id)){
						$distributionItem = Item::create([
							'company_id' => company_id(),
							'name' => $rowD['name'],
							'sku' => $rowD['code'],
							'description' => $rowD['name'],
							'quantity' => 1,
							'sale_price' => 0,
							'purchase_price' => 0,
							'created_from' => 'core::ui', 
							'created_by' => user()->id
						]);
					}

					$itemArrayData = [
						'company_id' => company_id(),
						'item_id' => $distributionItem->id,
						'name' => $distributionItem->name,
						'description' => $distributionItem->description,
						'type' => 'invoice',
						'quantity' => 1,
						'price' => $distributedAmount,		
						'total' => $distributedAmount,	
						'document_id' => $CN->id,
						'updated_at' => Carbon::now(),
						'created_by' => auth()->user()->id,
						'created_at' => Carbon::now()
					];
					$newDocItem = DocumentItem::create($itemArrayData);

					$paymentDivision = PaymentDivision::create([
						'church_id' => $payment->church_id,
						'regional_id' => $regionID,
						'sub_regional_id' => $sub_region,
						'contact_id' => 0,
						'type' => $rowD['code'],
						'amount' => $distributedAmount,
						'invoice_id' => $CN->id,
						'invoice_item_id' => $newDocItem->id,
						'year' => $itm->year,
						'month' => $itm->month
					]);

					$paymentDivision[$itm->month] = $distributedAmount;
					$paymentDivision->save();

					$sub_total += floatval($itemArrayData['total']);
				}
			}
			$totals = ['sub_total', 'total'];

			foreach($totals as $in=>$tt){
				$totalArr = [
					'company_id' => company_id(), 
					'type' => 'invoice', 
					'document_id' => $CN->id, 
					'code' => $tt, 
					'name' => 'invoices.'.$tt, 
					'amount' => $sub_total, 
					'sort_order' => $in+1,
					'created_from' => 'core::ui',
					'created_by' => auth()->user()->id,
				];
				DocumentTotal::create($totalArr);
			}

			$NAmntOBJ = Document::find($CN->id);
			$NAmntOBJ->update(['amount' => $sub_total]);

			try {
				event(new \App\Events\Document\PaymentReceived($NAmntOBJ, ['type' => 'income', 'mark_paid' => 'invoice']));
			
				$message = trans('documents.messages.marked_paid', ['type' => trans_choice('general.invoices', 1)]);
			
				flash($message)->success();
			} catch(\Exception $e) {
				$message = $e->getMessage();
			
				flash($message)->error()->important();
			}
		}
		return true;
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
