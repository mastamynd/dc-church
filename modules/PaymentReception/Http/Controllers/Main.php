<?php

namespace Modules\PaymentReception\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\Document\Document;
use App\Models\Document\DocumentItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Church\Models\Church;
use Modules\PaymentReception\Models\PaymentReception;
use Modules\PaymentReception\Models\PaymentReceptionItems;

class Main extends Controller
{
	public function paymentIndex(){
		$payments = PaymentReception::orderBy('status', 'asc')->collect();

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
	}

	public function create_invoice($payment){
		$INV = Document::invoice()->orderBy('id', 'asc')->first();

		$ITEMS = $payment->items;

		foreach($ITEMS as $itm){
			$CNNxtID = setting('invoice.number_next');
			$DocNo = 'INV-'.str_pad($CNNxtID,5,"0",STR_PAD_LEFT);
			$originalArr = $INV->toArray();

			$updateArr = [
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

			$newData = array_merge($originalArr, $updateArr);

			unset($newData['id']);
			unset($newData['totals']);
			unset($newData['transactions']);
			unset($newData['media']);
			unset($newData['footer']);

			$CN = Document::create($newData);

			$CNNxtID += 1;

			setting(['invoice.number_next'=> $CNNxtID])->save();

			$sub_total = 0;
			$discount_total = 0;

			$invoice_items = setting(['church.payment_divisions']);

			foreach($invoice_items as $rowD){
				if($rowD['code'] != 'pastors_fund'){
					$church = Church::find($payment->church_id);
					$pastors = $church->pastors ?? [];
					$noCount = count($pastors);

					if($noCount == 1){
						foreach($pastors as $pastor){
							$
						}						
					}
					else if($noCount == 2){

					}
					else{

					}
				}
				else{

				}
				$ci =  DocumentItem::where('document_id', $invID)->where('item_id', $iDs)->first();
			
				if(!isset($ci->id)){
					continue;
				}



				$oldData = $ci->toArray();

				$newDataCI = [
					'type' => 'invoice',
					'price' => $prices[$ci->item_id],		
					'total' => $prices[$ci->item_id],	
					'document_id' => $CN->id,
					'updated_at' => Carbon::now(),
					'created_by' => auth()->user()->id,
					'created_at' => Carbon::now()
				];

				$cnItem = array_merge($oldData, $newDataCI);

				if($ci->discount_rate > 0){
					$discount_amnt = $ci->discount_type == "percentage" ? floatval($ci->discount_rate)*$ci->price : $ci->discount_rate;
					$cnItem['total'] = $ci->price - $discount_amnt;

					$discount_total += floatval($discount_amnt);
				}
				$sub_total += floatval($cnItem['total']);

				DocumentItem::create($cnItem);
			}
		}
	}

}
