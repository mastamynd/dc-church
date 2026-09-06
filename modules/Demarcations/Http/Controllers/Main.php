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
use Modules\Demarcations\Models\DemarcationHistory;
use Modules\Demarcations\Models\DemarcationOverseer;
use App\Http\Requests\Banking\Transaction as TransRequest;
use App\Http\Requests\Common\Import as ImportRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Church\Models\Church;
use Modules\Church\Models\ChurchHistory;
use Modules\Demarcations\Exports\DemarcationBanksExport;
use Modules\Demarcations\Exports\DemarcationExport;
use Modules\Demarcations\Imports\DemarcationBanks;
use Modules\Demarcations\Models\Bank;

class Main extends Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('permission:read-demarcations-main')->only('exportBanks');
		$this->middleware('permission:update-demarcations-main')->only('importBanks');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function region_index()
	{
		$type = 'region';
		$demarcations = Demarcation::with('parent')->leftJoin('demarcation_overseers as do', function ($query) {
			$query->on('do.demarcation_id', 'demarcations.id');
			$query->whereNull('do.deleted_at');
		})->leftJoin('pastors as p', function ($query) {
			$query->on('p.id', 'do.pastor_id');
		})->leftJoin('contacts as c', function ($query) {
			$query->on('c.id', 'p.contact_id');
		})->where('demarcations.type', 'Region')->select(['demarcations.*', 'c.name as dname', 'c.phone'])->collect();

		// return response()->json($demarcations, 200);

		return $this->response('demarcations::index', compact('demarcations', 'type'));
	}

	public function subregion_index()
	{
		$type = 'subregion';
		$demarcations = Demarcation::with('parent')->leftJoin('demarcation_overseers as do', function ($query) {
			$query->on('do.demarcation_id', 'demarcations.id');
			$query->whereNull('do.deleted_at');
		})->leftJoin('pastors as p', function ($query) {
			$query->on('p.id', 'do.pastor_id');
		})->leftJoin('contacts as c', function ($query) {
			$query->on('c.id', 'p.contact_id');
		})->where('demarcations.type', 'Sub Region')->select(['demarcations.*', 'c.name as dname', 'c.phone'])->collect();

		// return response()->json($demarcations, 200);

		return $this->response('demarcations::index', compact('demarcations', 'type'));
	}

	public function getNextDemarcationCode($type, $increment = false)
	{
		$demAutoIncStr = $type == "region" ? 'region_code_number' : 'subregion_code_number';
		$demPrefixStr = $type == "region" ? 'region_prefix' : 'subregion_prefix';

		$demPrefix = setting('church.' . $demPrefixStr);
		$demarcationAutoInc = setting($demAutoIncStr);

		$possible_next_code = $demPrefix . $demarcationAutoInc;

		if ($increment) {
			setting([$demAutoIncStr => $demarcationAutoInc + 1])->save();
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
		$banks = $this->banksForSelect();

		return view('demarcations::create', compact('type', 'regions', 'possible_next_code', 'banks'));
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
		$request->merge(['code' => $possible_next_code]);
		$this->normalizeBankingInput($request);

		// return json_encode($request->all());

		$demarcation = Demarcation::create($request->all());

		if ($type == 'subregion') {
			DemarcationHistory::create([
				'demarcation_id' => $demarcation->id,
				'parent_id' => $request->region_id
			]);
		}
		flash(ucwords($type) . ' has been created!')->success();
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
		$demarcation = Demarcation::with('bank')->find($id);

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
		$banks = $this->banksForSelect();

		// return response()->json($demarcation, 200);

		return view('demarcations::edit', compact('demarcation', 'regions', 'type', 'possible_next_code', 'banks'));
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
		$this->normalizeBankingInput($request);
		$demarcation->update($request->all());

		// return response()->json($demarcation, 200);

		if ($demarcation->type == "Sub Region" && (!isset($demarcation->parent) ? 0 : $demarcation->parent->parent_id != $request->region_id)) {
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
		DemarcationOverseer::where('demarcation_id', $id)->delete();
		DemarcationOverseer::create([
			'demarcation_id' => $id,
			'pastor_id' => $request->pastor_id
		]);
		flash("Overseer Updated")->success();
		return redirect()->back();
	}

	public function stop_overseer(Request $request, $id=false)
	{
		// return response()->json($request->all());
		if($request->has('delete') && $request->delete == 1){
			DemarcationOverseer::where('id', $id)->forceDelete();
		}
		else{
			DemarcationOverseer::where('id', $id)->delete();
		}
		flash("Overseer History Updated")->success();
		return redirect()->back();
	}

	public function destroy($id)
	{
		$demarcation = Demarcation::find($id);
		$type = $demarcation->type;
		$demarcation->delete();
		DemarcationHistory::where('demarcation_id', $id)->delete();
		DemarcationOverseer::where('demarcation_id', $id)->delete();


		flash($type . " was deleted successfully")->success();
		return redirect()->back();
	}

	public function export(Request $request)
	{
		$type = $request->type == 'region' ? "Region" : 'Sub Region';

		$demarcations = Demarcation::with('parent')->leftJoin('demarcation_overseers as do', function ($query) {
			$query->on('do.demarcation_id', 'demarcations.id');
			$query->whereNull('do.deleted_at');
		})->leftJoin('pastors as p', function ($query) {
			$query->on('p.id', 'do.pastor_id');
		})->leftJoin('contacts as c', function ($query) {
			$query->on('c.id', 'p.contact_id');
		})->leftJoin('banks as b', function ($query) {
			$query->on('b.id', 'demarcations.bank_id');
			$query->whereNull('b.deleted_at');
		})->where('demarcations.type',  $type)->select(['demarcations.*', 'c.name as dname', 'c.phone', 'b.name as bank_name'])->orderBy('demarcations.name')->get();

		return Excel::download(new DemarcationExport($demarcations, $type), $request->type . '-list.xlsx');
	}

	public function exportBanks()
	{
		return Excel::download(new DemarcationBanksExport, 'region-subregion-banking-details.xlsx');
	}

	public function importBanks(ImportRequest $request)
	{
		$type = Str::contains($request->route()->getName(), 'subregion') ? 'Sub Region' : 'Region';
		$importType = $type === 'Sub Region' ? 'subregion-banks' : 'region-banks';
		$indexRoute = $type === 'Sub Region' ? 'demarcations.subregion.index' : 'demarcations.region.index';

		$response = $this->importExcel(new DemarcationBanks($type), $request, trans_choice('demarcations::general.' . str_replace('-', '_', $importType), 2));

		if ($response['success']) {
			$response['redirect'] = route($indexRoute);

			flash($response['message'])->success();
		} else {
			$response['redirect'] = route('import.create', ['demarcations', $importType]);

			flash($response['message'])->error()->important();
		}

		return response()->json($response);
	}

	public function make_payments(Request $request, $region)
	{
		// return response()->json($request->all(), 200);

		$items = $request->items;
		$theCONTACT = Contact::vendor()->whereIn('email', ['payment-maker@deliverancechurch.international', 'make-payments@deliverancechurch.international'])->first();

		$time = time();

		$BILLZ = [];

		$DATAS = [];

		foreach ($items as $type => $DD) {
			foreach ($DD as $demID => $data) {
				$itemName = Demarcation::find($demID);
				$CNNxtID = setting('bill.number_next');
				$DocNo = 'BILL-' . str_pad($CNNxtID, 5, "0", STR_PAD_LEFT);
				$newData = [
					'contact_id' => $theCONTACT->id,
					'company_id' => company_id(),
					'currency_code' => 'KES',
					'currency_rate' => 1,
					'order_number' => $time,
					'contact_country' => 'KE',
					'contact_name' => ($type == 'sub_region' ? $itemName->name : $itemName->name) . ' - ' . ucwords((implode(' ', (explode('_', $type))))),
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
				setting(['bill.number_next' => $CNNxtID])->save();

				$BILLZ[$type] = $BILLL;

				foreach ($data as $dt => $dd) {
					$dts = explode('-', $dt);

					if (!isset($DATAS[$type])) {
						$DATAS[$type] = [];
					}

					$distributionItem = Item::where('name', $itemName->name . ' Payment Line')->first();
					if (!isset($distributionItem->id)) {
						$distributionItem = Item::create([
							'company_id' => company_id(),
							'name' => $itemName->name . ' Payment Line',
							'sku' => $itemName->name . ' Payment Line',
							'description' => $itemName->name . ' Payment Line',
							'quantity' => 1,
							'sale_price' => 0,
							'purchase_price' => 0,
							'created_from' => 'core::ui',
							'created_by' => user()->id
						]);
					}

					if (!isset($BILLITEMS[$distributionItem->name])) {
						$BILLITEMS[$distributionItem->name] = ['name' => $distributionItem->name, 'id' => $distributionItem->id, 'total' => 0, 'description' => [], 'items' => []];
					}

					$BILL = $BILLZ[$type];

					foreach ($dts as $c) {
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

				foreach ($BILLITEMS as $itt) {
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

					$PESA += $itt['total'];
				}


				$totals = ['sub_total', 'total'];

				foreach ($totals as $in => $tt) {
					$totalArr = [
						'company_id' => company_id(),
						'type' => 'bill',
						'document_id' => $BILL->id,
						'code' => $tt,
						'name' => 'bills.' . $tt,
						'amount' => $PESA,
						'sort_order' => $in + 1,
						'created_from' => 'core::ui',
						'created_by' => auth()->user()->id,
					];
					DocumentTotal::create($totalArr);
				}

				if (isset($BILLZ[$type])) {
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

	public function mark_paid(Request $request)
	{
		// return response()->json($request->all());

		$paymentModes = [
			"Cash" => "offline-payments.cash.1",
			"Bank Transfer" => "offline-payments.bank_transfer.2",
			"M-PESA" => "offline-payments.m_pesa.3",
			"Cheque" => "offline-payments.cheque.4"
		];


		foreach ($request->bills as $id) {

			$bill = Document::find($id);

			if (!isset($bill->id)) {
				continue;
			}

			$div = PaymentDivision::where('bill_id', $bill->id)->first();


			if (!isset($div->id)) {
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
				'amount' => abs($bill->amount_due),
				'currency_code' => 'KES',
				'currency_rate' => 1,
				'document_id' => null,
				'contact_id' => $bill->contact_id,
				'description' => $bill->description,
				'category_id' => 4,
				'payment_method' => $paymentModes["Cheque"],
				'reference' => $bill->order_number,
				'parent_id' => 0,
				'created_from' => 'core::ui',
				'created_by' => \Auth::user()->id,
				'do_not_fire_event' => true
			];

			$myRequest = new TransRequest($payable_amnts);

			$billC = new \App\Http\Controllers\Modals\DocumentTransactions();
			$test = $billC->store($bill, $myRequest);
		}

		flash('Payments Made Successfully')->success();

		return redirect()->back();
	}

	public function payments_made(Request $request)
	{
		$regions = Demarcation::with('overseer.pastor.contact')
			->region()
			->orderBy('name', 'asc')
			->get();

		$regions->each->setAppends([]);
		Demarcation::hydratePaymentTotals($regions);

		return $this->response('demarcations::reports.payments-made', compact('regions'));
	}

	public function payment_distribution(Request $request, $type, $type_id)
	{
		$type = strtolower($type);
		$demarcation = Demarcation::with('overseer.pastor.contact')->where('id', $type_id)->first();

		if ($demarcation) {
			$demarcation->setAppends([]);
		}

		if ($type == "region") {
			$children = DemarcationHistory::with('demarcation.overseer.pastor.contact')->where('parent_id', $type_id)->get();
		} else {
			$children = ChurchHistory::with('church.senior_pastor.pastor.contact')->where('demarcation_id', $type_id)->get();
		}

		$column = $type == 'region' ? 'regional_id' : 'sub_regional_id';

		$paymentList = PaymentDivision::query()
			->selectRaw('SUM(COALESCE(amount, 0)) as amount, type, cheque_number, MAX(payment_date) as payment_date')
			->whereNotNull('payment_date')
			->where($column, $type_id)
			->groupBy('type', 'cheque_number')
			->orderByDesc(DB::raw('MAX(payment_date)'))
			->get();

		return $this->response('demarcations::reports.payments-made-show', compact('type', 'demarcation', 'children', 'paymentList'));
	}

	public function view_cheque_number_list(Request $request, $cheque_number, $type)
	{
		$paymentList = PaymentDivision::query()
			->leftJoin('pastor_histories as ph', function ($join) {
				$join->on('ph.church_id', 'payment_divisions.church_id');
				$join->where('ph.rank', 'Senior');
				$join->whereNull('ph.deleted_at');
			})
			->leftJoin('pastors as p', 'p.id', 'ph.pastor_id')
			->leftJoin('contacts as c', 'c.id', 'p.contact_id')
			->with('church.senior_pastor.pastor.contact', 'pastor.contact', 'region', 'subregion')
			->select(['payment_divisions.*', 'c.name'])
			->where('payment_divisions.type', $type)
			->where('cheque_number', $cheque_number)
			->get();

		return $this->response('demarcations::reports.payment-made-division-list', compact('paymentList', 'cheque_number'));
	}

	public function pending_payments()
	{
		$divisions = setting('church.payment_divisions');
		$divisions = json_decode($divisions);

		$regions = Demarcation::region()->with('subregions.demarcation');

		if (request()->has('region')) {
			$regions = $regions->where('id', request()->region);
		}

		$regions = $regions->get();

		$allregs = Demarcation::region()->orderBy('name', 'asc')->get();

		// return response()->json($divisions);

		return $this->response('demarcations::payments', compact('divisions', 'regions', 'allregs'));
	}

	public function get_pending_items_by_dem($div, $id)
	{
		$payments = PaymentDivision::with(['church.senior_pastor.pastor.contact', 'subregion', 'pastor'])->whereNull('payment_date')->where('regional_id', $id)->where('type', $div)
			->selectRaw('id,church_id,regional_id,sub_regional_id,month,year,amount,contact_id')->get();

		$dataConfiged = [];

		foreach ($payments as $pay) {
			if (!isset($dataConfiged[$pay->sub_regional_id])) {
				$dataConfiged[$pay->sub_regional_id] = [];
			}

			$dataConfiged[$pay->sub_regional_id][] = $pay;
		}

		return response()->json(["data" => $dataConfiged], 200,);
	}

	protected function banksForSelect()
	{
		return Bank::enabled()->orderBy('name')->get(['id', 'name', 'code']);
	}

	protected function normalizeBankingInput(Request $request): void
	{
		$request->merge([
			'bank_id' => $request->filled('bank_id') ? $request->bank_id : null,
			'paybill' => $request->filled('paybill') ? trim($request->paybill) : null,
			'account_number' => $request->filled('account_number') ? trim($request->account_number) : null,
		]);

		$request->validate([
			'bank_id' => 'nullable|integer|exists:banks,id,company_id,' . company_id() . ',deleted_at,NULL',
			'paybill' => 'nullable|string|max:50',
			'account_number' => 'nullable|string|max:50',
		]);
	}
}
