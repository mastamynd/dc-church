<?php
namespace Modules\PaymentReception\Http\Controllers\API;

use App\Abstracts\Http\ApiController as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Church\Models\Church;
use Modules\Church\Models\Pastor;

use Modules\PaymentReception\Http\Controllers\Main as PaymentController;
use Modules\PaymentReception\Models\IntegrationPaymentPayload;

class Main extends Controller{
	public function church(Request $request){
		// $church = Church::join('pastor_histories as ph', function($join){
		// 	$join->on('ph.church_id', 'churches.id');
		// 	$join->where('ph.rank', 'Senior');
		// 	$join->whereNull('deleted_at');
		// })->where('code', $request->code)->select('churches.code', DB::raw('churches.name as church'), DB::raw('CONCAT(ph.position, " ", ph)'))->first();

		$church = Church::where('code', $request->code)->first();

		if(!isset($church->id)){
			return response()->json([
				'status' => false,
				'message' => 'Church not found. Ensure church code matches DCIxxxx'
			]);
		}

		$senior = Pastor::join('pastor_histories as ph', function($join){
			$join->on('ph.pastor_id', 'pastors.id');
			$join->whereNull('ph.deleted_at');
		})->select('pastors.name', 'ph.position')->where('ph.rank', 'Senior')->where('ph.church_id', $church->id)->first();

		if(!isset($senior->id)){
			$pastor = Pastor::join('pastor_histories as ph', function($join){
				$join->on('ph.pastor_id', 'pastors.id');
				$join->whereNull('ph.deleted_at');
			})->select('pastors.name', 'ph.position')->where('ph.church_id', $church->id)->first();
		}

		return response()->json([
			'code' => $request->code,
			'church' => $church->name,
			'senior' => isset($senior->name) ? ($senior->position." ".$senior->name) : (isset($pastor->name) ? ($pastor->position." ".$pastor->name) : '-')
		]);
	}

	public function reception(Request $request){
		if(!isset($request->code)){
			return response()->json([
				'status' => false,
				'message' => 'Missing church code'
			]);
		}

		if(!isset($request->type) || $request->type==""){
			return response()->json([
				'status' => false,
				'message' => 'Missing payment type.'
			]);
		}

		if(!isset($request->amount) || floatval($request->amount) <= 0){
			return response()->json([
				'status' => false,
				'message' => 'Missing payment amount or provided amount is 0.'
			]);
		}

		$lastSunday = \Carbon\Carbon::parse('last sunday');
		$church = Church::where('code', $request->code)->first();

		if(!isset($request->month) || trim($request->month) == ""){
			$request->month = $lastSunday->format('F');
		}

		if(!isset($request->week) || trim($request->week) == ""){
			$request->week = $lastSunday->weekOfMonth;
		}

		if($request->type == "tithe"){
			$requestArray = [
				'church_id' => $church->id, 
				'payment_mode' => isset($request->payment_mode) ? 'mpesa' : 'ussd', 
				'reference' => $request->phone, 
				'phone_number' => $request->phone, 
				'payer_name' => 'INTEGRATION USER', 
				'payer_phone' => $request->phone, 
				'payer_email' => '-', 
				'rows' => [
					['year' => $request->year, 'month' => $request->month, 'week' => $request->week, 'amount' => $request->amount, 'topup' => $request->phone]
				]
			];

			$paymentC = new PaymentController();
			$REQ = new Request($requestArray);
			$paymentC->store($REQ, 0);
		}

		IntegrationPaymentPayload::create([
			'type' => $request->type,
			'payload' => json_encode($request->all())
		]);

		return response()->json(['status'=>true, 'message' => 'Payment Received Successfully']);
	}
}