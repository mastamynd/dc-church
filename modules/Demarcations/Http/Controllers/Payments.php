<?php

namespace Modules\Demarcations\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\Document\Document;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Church\Models\PaymentDivision;
use Modules\Demarcations\Models\Demarcation;
use Modules\Demarcations\Models\DemarcationHistory;

class Payments extends Controller
{
	public function remove_underscore($type)
	{
		return ucwords(implode(" ", explode("_", $type)));
	}

	public function getTypes($request)
	{
		$divisions = setting('church.payment_divisions');
		$divisions = json_decode($divisions, true);

		$theList = [];

		$paymentsList = PaymentDivision::selectRaw('SUM(amount+topup) as total, type')->whereNull('payment_date');

		if ($request->region) {
			$paymentsList = $paymentsList->where('regional_id', $request->region);
		}
		if ($request->sub_region && trim($request->sub_region) != "") {
			$paymentsList = $paymentsList->where('regional_id', $request->sub_region);
		}

		$paymentsList = $paymentsList->groupBy('type')->get();

		foreach ($divisions as $k => $v) {
			$theList[$v['code']] = $v;
		}

		foreach ($paymentsList as $list) {
			$theList[$list->type]['amount'] = $list->total;
		}

		return $theList;
	}

	public function payment_index(Request $request)
	{
		// return $this->removeDuplicateInvoices();

		$PaymentTypes = $this->getTypes($request);
		$regions = Demarcation::where('type', 'Region')->orderBy('name', 'asc')->get();

		$subs = [];

		if ($request->region) {
			$subs = json_decode($this->sub_region_list($request->region));
		}

		// return response()->json($subs);

		return $this->response('demarcations::make-payments.index', compact('PaymentTypes', 'regions', 'subs'));
	}

	public function sub_region_list($reg)
	{
		$subIDs = DemarcationHistory::where('parent_id', $reg)->select('demarcation_id')->get()->pluck('demarcation_id')->toArray();
		$subs = Demarcation::whereIn('id', $subIDs)->select('id', 'name')->get();

		return json_encode($subs);
	}

	public function payment_distribution_list(Request $request, $type, $reg = false)
	{
		$pageTitle = $this->remove_underscore($type);
		$regions = Demarcation::where('type', 'Region')->orderBy('name', 'asc')->get();

		$subs = [];

		$grandTotal = 0;

		$typAll = ['contribution_to_hq', 'dc_secretariate', 'pastors_fund', 'mission'];
		$hasPayAll = in_array($type, $typAll);
		$hasPayReg = $type == 'region';
		$hasPaySub = $type == 'sub_region';

		$RegionData = null;

		if ($request->has('region') && trim($request->region) != "" && !$request->has('sub_region')) {
			$subs = json_decode($this->sub_region_list($request->region));
			$RegionData = PaymentDivision::join('demarcations as d', 'regional_id', 'd.id')
				->where('payment_divisions.type', $type)->where('regional_id', $request->region)
				->selectRaw('DISTINCTROW SUM(amount) as total, SUM(topup) as topup, name, regional_id as id')
				->whereNull('payment_date')->first();

			$paymentDivision = PaymentDivision::join('demarcations as d', 'sub_regional_id', 'd.id')
				->where('payment_divisions.type', $type)->where('regional_id', $request->region)
				->selectRaw('DISTINCTROW SUM(amount) as total, SUM(topup) as topup, name, sub_regional_id as id')
				->whereNull('payment_date')->orderBy('name')->groupBy('sub_regional_id')->get();
			// echo 1.2;
		}
		if ($request->has('region') && $request->has('sub_region') && trim($request->sub_region) != "") {
			$dbPrefix = DB::getTablePrefix();
			$RegionData = PaymentDivision::join('demarcations as d', 'sub_regional_id', 'd.id')
				->where('payment_divisions.type', $type)->where('sub_regional_id', $request->sub_region)
				->selectRaw('DISTINCTROW SUM(amount) as total, SUM(topup) as topup, name, sub_regional_id as id')
				->whereNull('payment_date')->first();

			$paymentDivision = PaymentDivision::with('church', 'pastor')
				->leftJoin('pastor_histories as ph', function ($join) {
					$join->on('ph.church_id', 'payment_divisions.church_id');
					$join->where('ph.rank', 'Senior');
					$join->whereNull('ph.deleted_at');
				})
				->leftJoin('pastors as p', function ($join) {
					$join->on('p.id', 'ph.pastor_id');
				})
				->leftJoin('contacts as c', function ($join) {
					$join->on('c.id', 'p.contact_id');
				})
				->join('demarcations as d', 'sub_regional_id', 'd.id')
				->where('payment_divisions.type', $type)->where('sub_regional_id', $request->sub_region)
				->selectRaw('DISTINCTROW SUM(amount) as total, SUM(topup) as topup, month, year, ' . $dbPrefix . 'c.name as senior, ' . $dbPrefix . 'd.name, sub_regional_id as id, ' . $dbPrefix . 'payment_divisions.church_id, ' . $dbPrefix . 'payment_divisions.contact_id')
				->whereNull('payment_date');

			if ($type == "pastors_fund") {
				$paymentDivision = $paymentDivision->groupBy('contact_id');
			}

			$paymentDivision = $paymentDivision->groupBy('church_id')->groupBy('month')->groupBy('year')
				->orderBy('church_id')->orderBy('payment_divisions.id', 'asc')->get();
			// echo 1.1;
		}

		if (!$request->has('region') && !$request->has('sub_region')) {
			$paymentDivision = PaymentDivision::join('demarcations as d', 'regional_id', 'd.id')
				->where('payment_divisions.type', $type)
				->selectRaw('DISTINCTROW SUM(amount) as total, SUM(topup) as topup, name, regional_id as id')
				->whereNull('payment_date')->orderBy('name')->groupBy('regional_id')->get();
				// echo 1;
		}

		foreach ($paymentDivision as $pd) {
			if ($type == 'pastors_fund') {
				$grandTotal += ($pd->total + $pd->topup);
			} else {
				$grandTotal += ($pd->total);
			}
		}

		// return response()->json($paymentDivision);

		return $this->response('demarcations::make-payments.distribution', compact('type', 'grandTotal', 'hasPayAll', 'hasPayReg', 'hasPaySub', 'RegionData', 'paymentDivision', 'regions', 'subs', 'pageTitle'));
	}

	public function pay_cheque_numbers(Request $request)
	{

		// return response()->json($request->all());

		foreach ($request->payment as $type => $data) {
			if (gettype($data) == "array") {
				foreach ($data as $reg => $data2) {
					if (gettype($data2)  == "array") {
						foreach ($data2 as $sub => $chn) {
							$divisions = PaymentDivision::whereNull('payment_date');
							$divisions = $divisions->where('type', $type);
							$divisions = $divisions->where('regional_id', $reg);
							$divisions = $divisions->where('sub_regional_id', $sub);
							$divisions = $divisions->update([
								"payment_date" => $request->pay_date,
								"cheque_number" => $chn
							]);
						}
					} else {
						$divisions = PaymentDivision::whereNull('payment_date');
						$divisions = $divisions->where('type', $type);
						$divisions = $divisions->where('regional_id', $reg);
						$divisions = $divisions->update([
							"payment_date" => $request->pay_date,
							"cheque_number" => $data2
						]);
					}
				}
			} else {
				$divisions = PaymentDivision::whereNull('payment_date');
				$divisions = $divisions->where('type', $type);
				$divisions = $divisions->update([
					"payment_date" => $request->pay_date,
					"cheque_number" => $data
				]);
			}
		}

		flash("Cheque Number Saved")->important();
		return redirect()->back();
	}

	public function removeDuplicateInvoices(){
		$duplicateDocs = Document::selectRaw('count(id) as dup, group_concat(id) as ids')
			->groupBy('parent_id')->having('dup', '>', 1)->get();

		$invoices = [];
		$allInvoices = [];
		$toDelete = [];

		foreach($duplicateDocs as $doc){
			$parts = explode(',',$doc->ids);
			$lastDoc = $parts[count($parts) - 1];
			$allInvoices = array_merge($allInvoices, $parts);
			$invoices[] = $lastDoc;

			foreach($parts as $pt){
				if($pt != $lastDoc){
					$toDelete[] = $pt;
				}
			}
		}

		$invoices = array_filter($invoices);
		$allInvoices = array_filter($allInvoices);

		Document::whereIn('id', $toDelete)->delete();
		PaymentDivision::whereIn('invoice_id', $toDelete)->delete();

		return response()->json(["all"=>$allInvoices,"ignore"=>$invoices, "delete"=>$toDelete], 200);
	}
}
