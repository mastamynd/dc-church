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
use Modules\Church\Models\Church;
use Modules\Church\Models\PaymentDivision;
use Modules\PaymentReception\Models\PaymentReception;
use Modules\PaymentReception\Models\PaymentReceptionItems;

class Main extends Controller
{
	public function paymentIndex(){
		$tableRefix = DB::getTablePrefix();

		$payments = PaymentReception::with('church')->join('payment_reception_items as pri', 'pri.payment_reception_id', 'payment_receptions.id')
				->selectRaw($tableRefix.'payment_receptions.id, church_id, month, payment_mode, SUM(amount) as amount, payer_name, status, '.$tableRefix.'payment_receptions.created_at')
				->groupBy('church_id','month','year')->orderBy('created_at', 'desc')->orderBy('status', 'asc')->collect();

		// return response()->json($payments, 200);

		return $this->response('payment-reception::index', compact('payments'));
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function paymentShow($id='create')
	{
		$tablePrefix = DB::getTablePrefix();
		$churches = Church::leftJoin('church_histories as ch', function($join){
			$join->on('ch.church_id', 'churches.id');
			$join->whereNull('ch.deleted_at');
		})
		->leftJoin('demarcations as d', function($join){
			$join->on('d.id', 'ch.demarcation_id');
			$join->whereNull('d.deleted_at');
		})
		->select('churches.id', 'churches.name', 'churches.code', DB::raw('CONCAT('.$tablePrefix.'churches.code, " - ", '.$tablePrefix.'churches.name, ", ", '.$tablePrefix.'d.name) as codename'));

		$payment_modes = ['mpesa'=>'M-Pesa', 'cash'=>'Cash', 'bank deposit'=>'Bank Deposit', 'ussd'=>'USSD', 'portal'=>'Portal'];
		$start_year = 2018;
		$paymentYear = date('Y');
		$paymentMonth = date('M');
		$paymentWeek = \Carbon\Carbon::now()->weekOfMonth;
		$paymentTopup = 0;
		$weeks = [1,2,3,4,5];
		$months = [
			'long' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
			'short' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec']
		];

		$paymentReception = PaymentReception::find($id);

		if(isset($paymentReception->id)){
			$churches = $churches->where('churches.id', $paymentReception->church_id);
		}

		$churches = $churches->orderBy('churches.name', 'asc')->limit(10)->get();

		$missing = [$id];

		$lastReceiptions = PaymentReception::join('churches as c', 'c.id', 'payment_receptions.church_id')->join('payment_reception_items as pri', 'pri.payment_reception_id', 'payment_receptions.id')
			->whereNotIn('payment_receptions.id', $missing)->select('c.name', 'c.code', 'payment_receptions.id', 'pri.month', 'pri.year', 'payer_name', 'pri.amount')
			->orderBy('pri.created_at', 'desc')->limit(10)->get();

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

		$existsBefore = [];


		$payment = intval($id) > 0 ? PaymentReception::find($id) : PaymentReception::create($dataArr);

		foreach($rows as $k=>$row){
			$previousReceptionsIDs = PaymentReception::where('church_id', $dataArr['church_id'])->select('id')->get()->pluck('id')->toArray();
			$idsTocheck = array_diff($previousReceptionsIDs, [$payment->id]);
			$before = PaymentReceptionItems::where('month', $row['month']);
			
			if(isset($row['week']) && trim($row['week']) != ""){
				$before = $before->where('week', $row['week']);
			}

			$before = $before->where('year', $row['year'])->whereIn('payment_reception_id', $idsTocheck)->get();
			if($before->count() > 0){
				$existsBefore[] = $before;
			}
		}

		// return json_encode($existsBefore);

		if(count($existsBefore) > 0){
			flash('Payment for similar period exists')->important()->error();
			return redirect()->route('payment-reception.index');
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

		if($payment->status == "Completed"){
			flash('Tithe has already been submitted!')->important()->error();
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
					$distributedAmount+=floatval($itm->topup ?? 0);
					$pastors = $church->pastors ?? [];
					$noCount = count($pastors);

					$split = $splitCriteria[$noCount] ?? ['Senior'=>40, 'Assistant'=>60];

					$splitAmounts = [];

					foreach($split as $s=>$p){
						$splitAmounts[$s] = $p/100*$distributedAmount;
					}

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

						if($noCount == 1){
							$price = $distributedAmount;
						}
						else
						{
							if($pastor->rank == 'Senior'){
								$price = $paymentBracketAmount; 
							}
							else{
								$otherRanks = $noCount-1;
								$price = $paymentBracketAmount/$otherRanks;
							}
						}

						$itemArrayData = [
							'company_id' => company_id(),
							'item_id' => $pastorItem->id,
							'name' => $pastorItem->name,
							'description' => $pastorItem->description,
							'type' => 'invoice',
							'price' => $price,		
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

		$payment->delete();
		return redirect()->back();
	}

	public function reverse_post($id){
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
}
