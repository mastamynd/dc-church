<?php

namespace Modules\Demarcations\Http\Controllers;

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
use Modules\Church\Models\PaymentDivision;
use Modules\Church\Models\PaymentDivisionView;
use Modules\Demarcations\Models\DemarcationHistory;
use Modules\Demarcations\Models\DemarcationOverseer;
use App\Http\Requests\Banking\Transaction as TransRequest;
use Illuminate\Support\Facades\DB;
use Modules\Church\Models\Church;
use Modules\Church\Models\PaymentMadeView;

class Main extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function region_index()
	{
		$type = 'region';
		$demarcations = Demarcation::where('type', 'Region')->collect();
		return $this->response('demarcations::index', compact('demarcations', 'type'));
	}

	public function subregion_index(){
		$type = 'subregion';
		$demarcations = Demarcation::where('type', 'Sub Region')->collect();

		// return response()->json($demarcations, 200);

		return $this->response('demarcations::index', compact('demarcations', 'type'));
	}

	public function getNextDemarcationCode($type, $increment=false){
		$demAutoIncStr = $type == "region" ? 'region_code_number' : 'subregion_code_number';
		$demPrefixStr = $type == "region" ? 'region_prefix' : 'subregion_prefix';

		$demPrefix = setting('church.'.$demPrefixStr);
		$demarcationAutoInc = setting($demAutoIncStr);

		$possible_next_code = $demPrefix.$demarcationAutoInc;

		if($increment){
			setting([$demAutoIncStr => $demarcationAutoInc+1])->save();
		}

		return $possible_next_code;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($type = 'region')
	{
		$possible_next_code = $this->getNextDemarcationCode($type);

		// return $possible_next_code;

		$regions = Demarcation::region()->orderBy('name', 'asc')->get();
		return view('demarcations::create', compact('type', 'regions', 'possible_next_code'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	*/
	public function store(Request $request, $type)
	{
		$possible_next_code = $this->getNextDemarcationCode($type, true);
		$request->merge(['code'=>$possible_next_code]);

		// return json_encode($request->all());

		$demarcation = Demarcation::create($request->all());

		if($type == 'subregion'){
			DemarcationHistory::create([
				'demarcation_id' => $demarcation->id,
				'parent_id' => $request->region_id
			]);
		}
		flash(ucwords($type).' has been created!')->success();
		return redirect()->back();
	}

	/**
	 * Show the specified resource.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function show($type, $id)
	{
		$demarcation = Demarcation::find($id);

		// return response()->json($demarcation->pastors_fund('paid'), 200);

		$subregions = Demarcation::subregion()->orderBy('name', 'asc')->get();

		$invoice_items = setting('church.payment_divisions');
		$invoice_items = json_decode($invoice_items, true);
		return view('demarcations::show', compact('demarcation', 'type', 'subregions', 'invoice_items'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function edit($type, $id)
	{
		$possible_next_code = $this->getNextDemarcationCode($type);
		$regions = Demarcation::region()->orderBy('name', 'asc')->get();
		$demarcation = Demarcation::find($id);
		$type = $demarcation->type;

		// return response()->json($demarcation, 200);

		return view('demarcations::edit', compact('demarcation', 'regions', 'type','possible_next_code'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request $request
	 * @param int $id
	 * @return Response
	 */
	public function update(Request $request, $type, $id)
	{
		$demarcation = Demarcation::find($id);
		$demarcation->update($request->all());

		// return response()->json($demarcation, 200);

		if($demarcation->type == "Sub Region" && (!isset($demarcation->parent) ? 0 : $demarcation->parent->parent_id != $request->region_id)){
			DemarcationHistory::where('demarcation_id', $demarcation->id)->delete();

			DemarcationHistory::create([
				'demarcation_id' => $demarcation->id,
				'parent_id' => $request->region_id
			]);
		}

		return $demarcation->type == "Sub Region" ? redirect()->route('demarcations.subregion.index') : redirect()->route('demarcations.region.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function set_overseer(Request $request, $id)
	{
		DemarcationOverseer::where('id', $id)->delete();
		DemarcationOverseer::create([
			'demarcation_id' => $id,
			'pastor_id' => $request->pastor_id
		]);
		flash("Overseer Updated")->success();
		return redirect()->back();
	}

	public function destroy($id){
		$demarcation = Demarcation::find($id);
		$type = $demarcation->type;
		$demarcation->delete();
		DemarcationHistory::where('demarcation_id', $id)->delete();
		DemarcationOverseer::where('demarcation_id', $id)->delete();
		

		flash($type." was deleted successfully")->success();
		return redirect()->back();
	}

	public function export(){
		$data = Demarcation::with('overseer.pastor', 'subregions', 'subregions.demarcation', 'subregions.demarcation.overseer.pastor', 'subregions.demarcation.children.church', 'subregions.demarcation.children.church.senior_pastor')->get();

		return response()->json($data, 200);
	}

	public function make_payments(Request $request, $region){
		// return response()->json($request->all(), 200);

		$items = $request->items;
		$theCONTACT = Contact::vendor()->whereIn('email', ['payment-maker@deliverancechurch.international', 'make-payments@deliverancechurch.international'])->first();

		$time = time();

		$BILLZ = [];

		$DATAS = [];

		foreach($items as $type=>$DD){
			foreach($DD as $demID=>$data){
				$itemName = Demarcation::find($demID);
				$CNNxtID = setting('bill.number_next');
				$DocNo = 'BILL-'.str_pad($CNNxtID,5,"0",STR_PAD_LEFT);
				$newData = [
					'contact_id' => $theCONTACT->id,
					'company_id' => company_id(),
					'currency_code' => 'KES',
					'currency_rate' => 1,
					'order_number' => $time,
					'contact_country' => 'KE',
					'contact_name' => ($type == 'sub_region' ? $itemName->name : $itemName->name).' - '.ucwords((implode(' ', (explode('_', $type))))),
					'contact_email' => $theCONTACT->email,
					'parent_id' => 0,
					'status' => 'received',
					'type' => 'bill',
					'document_number' => $DocNo,
					'created_by' => auth()->user()->id,
					'created_at' => Carbon::now(),
					'issued_at' => Carbon::now(),
					'due_at' => Carbon::now(),
					'updated_at' => Carbon::now(),
					'regional_id' => $region,
					'sub_regional_id' => $type == 'sub_region' ? $demID : 0,
					'amount' => 0
				];

				$BILLITEMS = [];

				$BILLL = Document::create($newData);
				$CNNxtID += 1;
				setting(['bill.number_next'=> $CNNxtID])->save();

				$BILLZ[$type] = $BILLL;

				foreach($data as $dt=>$dd){
					$dts = explode('-', $dt);

					if(!isset($DATAS[$type])){
						$DATAS[$type] = [];
					}
					
					$distributionItem = Item::where('name', $itemName->name.' Payment Line')->first();
					if(!isset($distributionItem->id)){
						$distributionItem = Item::create([
							'company_id' => company_id(),
							'name' => $itemName->name.' Payment Line',
							'sku' => $itemName->name.' Payment Line',
							'description' => $itemName->name.' Payment Line',
							'quantity' => 1,
							'sale_price' => 0,
							'purchase_price' => 0,
							'created_from' => 'core::ui', 
							'created_by' => user()->id
						]);
					}

					if(!isset($BILLITEMS[$distributionItem->name])){
						$BILLITEMS[$distributionItem->name] = ['name'=>$distributionItem->name, 'id'=>$distributionItem->id, 'total'=>0, 'description'=>[], 'items'=>[]];
					}
		
					$BILL = $BILLZ[$type];

					foreach($dts as $c){
						$c = intval($c);
						$div = PaymentDivision::find($c);
						$church = Church::find($div->church_id);
						$div->bill_id = $BILL->id;
						$div->save();
						$BILLITEMS[$distributionItem->name]['total'] += floatval($div->amount);
						$BILLITEMS[$distributionItem->name]['items'][] = $div->id;
					};
				}

				$PESA = 0;

				foreach($BILLITEMS as $itt){
					$itemArrayData = [
						'company_id' => company_id(),
						'item_id' => $itt['id'],
						'name' => $itt['name'],
						'description' => implode(', ', $itt['description']),
						'type' => 'bill',
						'quantity' => 1,
						'price' => $itt['total'],		
						'total' => $itt['total'],	
						'document_id' => $BILL->id,
						'updated_at' => Carbon::now(),
						'created_by' => auth()->user()->id,
						'created_at' => Carbon::now()
					];

					$newDocItem = DocumentItem::create($itemArrayData);

					PaymentDivision::whereIn('id', $itt['items'])->update(['bill_item_id' => $newDocItem->id]);

					$PESA+=$itt['total'];
				}


				$totals = ['sub_total', 'total'];

				foreach($totals as $in=>$tt){
					$totalArr = [
						'company_id' => company_id(), 
						'type' => 'bill', 
						'document_id' => $BILL->id, 
						'code' => $tt, 
						'name' => 'bills.'.$tt, 
						'amount' => $PESA, 
						'sort_order' => $in+1,
						'created_from' => 'core::ui',
						'created_by' => auth()->user()->id,
					];
					DocumentTotal::create($totalArr);
				}

				if(isset($BILLZ[$type])){
					$BILL = $BILLZ[$type];
					$BILL->amount = $PESA;
					$BILL->save();
				}
			}
		}

		// return response()->json($BILLZ);

		flash('Payments Prepared')->success();
		return redirect()->back();
	}

	public function mark_paid(Request $request){
		// return response()->json($request->all());

		$paymentModes = [
			"Cash"=>"offline-payments.cash.1",
			"Bank Transfer"=>"offline-payments.bank_transfer.2",
			"M-PESA" => "offline-payments.m_pesa.3",
			"Cheque" => "offline-payments.cheque.4"
		];


		foreach($request->bills as $id){

			$bill = Document::find($id);

			if(!isset($bill->id)){
				continue;
			}

			$div = PaymentDivision::where('bill_id', $bill->id)->first();


			if(!isset($div->id)){
				flash("Missing Payment Division")->error();
				continue;
			}


			$bill->order_number = $request->cheque_number;
			$bill->due_at = $request->date_paid;
			$bill->save();

			$div = PaymentDivision::where('bill_id', $bill->id)->first();
			$div->payment_date = Carbon::now();
			$div->cheque_number = $request->cheque_number;
			$div->save();

			$payable_amnts = [
				'company_id' => company_id(),
				'type' => 'expense',
				'account_id' => 1,
				'paid_at' => $bill->due_at,
				'amount'=> abs($bill->amount_due),
				'currency_code' => 'KES',
				'currency_rate' => 1,
				'document_id'=> null,
				'contact_id' => $bill->contact_id,
				'description' => $bill->description,
				'category_id' => 4,
				'payment_method' => $paymentModes["Cheque"],
				'reference' => $bill->order_number,
				'parent_id' => 0,
				'created_from' => 'core::ui',
				'created_by'=> \Auth::user()->id,
				'do_not_fire_event' => true
			];

			$myRequest = new TransRequest($payable_amnts);

			$billC = new \App\Http\Controllers\Modals\DocumentTransactions();
			$test = $billC->store($bill, $myRequest);
		}

		flash('Payments Made Successfully')->success();

		return redirect()->back();
	}

	public function payments_made(){
		$dbprefix = DB::getTablePrefix();
		$payments = PaymentMadeView::withTrashed()->collect();

		return $this->response('demarcations::reports.payments-made', compact('payments'));
	}
}
