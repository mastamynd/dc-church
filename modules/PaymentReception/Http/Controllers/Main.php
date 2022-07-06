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
use Modules\Church\Models\Church;
use Modules\Church\Models\PaymentDivision;
use Modules\PaymentReception\Models\PaymentReception;
use Modules\PaymentReception\Models\PaymentReceptionItems;

class Main extends Controller
{
	public function paymentIndex(){
		$payments = PaymentReception::with('church')->orderBy('status', 'asc')->collect();

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
		$churches = Church::selectRaw('id, name, code, CONCAT(code, " - ", name) as codename');
		$payment_modes = ['mpesa'=>'M-Pesa', 'cash'=>'Cash', 'bank deposit'=>'Bank Deposit'];
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
			$churches = $churches->where('id', $paymentReception->church_id);
		}

		$churches = $churches->orderBy('name', 'asc')->limit(10)->get();

		return $this->response('payment-reception::edit', compact('paymentReception', 'start_year', 'churches', 'payment_modes', 'months', 'weeks', 'paymentYear', 'paymentMonth', 'paymentWeek'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request, $id)
	{
		$dataArr = $request->all();
		$rows = $dataArr['data'];
		
		unset($dataArr['_token']);
		unset($dataArr['data']);


		$payment = intval($id) > 0 ? PaymentReception::find($id) : PaymentReception::create($dataArr);

		// return json_encode($rows);

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

		return redirect()->route('payment-reception.view-tithe', $payment->id);
	}

	public function post_payment($id){
		$payment = PaymentReception::find($id);
		$this->create_invoice($payment);

		$payment->status = 'Completed';
		$payment->save();

		flash('Tithe Submission Approved!')->success();

		return redirect()->route('payment-reception.view-tithe', $id);
	}

	public function create_invoice($payment){
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
				$church = Church::find($payment->church_id);
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

}
