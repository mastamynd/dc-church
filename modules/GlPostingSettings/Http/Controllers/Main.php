<?php

namespace Modules\GlPostingSettings\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Exports\ExportCustomerStatement;
use App\Models\Custom\AgeAnalysisBand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\DB;

use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Document\Document;
use Modules\GlPostingSettings\Jobs\GlPostingReceiptJob;
use Modules\GlPostingSettings\Models\GlPostingReceipt;
use App\Http\Requests\Banking\Transaction as TransRequest;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\GlPostingSettings\Models\GlPostingSettingsSetting as Setting;
use Modules\GlPostingSettings\Models\OnlineReconsiliation as Recon;
use Modules\GlPostingSettings\Models\PaymentIntegrationConsensus as Consensus;
use Modules\GlPostingSettings\Models\ShearTransaction;

class Main extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index(Request $request)
	{
		$gl_configs = DB::table('view_gl_settings_entities');

		$searchTerm = '';

		$start = date('Y-m-d 00:00:00');

		$end = date('Y-m-d 23:59:59');

		if($request->has('start')){
			$start = $request->start;
		}
		
		if($request->has('end')){
			$end = $request->end;			
		}

		if($request->has('search')){
			$searchTerm = $request->search;
			$gl_configs = $gl_configs->where('searchableField', 'like', '%'.$searchTerm.'%');
		}

		if($request->has('sort')){
			$gl_configs = $gl_configs->orderBy($request->sort, $request->direction ?? 'asc');
		}

		$gl_configs = $gl_configs->paginate($request->has('limit') ? $request->limit : 25)->withQueryString();

		return $this->response('gl-posting-settings::index', compact('gl_configs', 'searchTerm', 'start', 'end'));
	}

	public function get_accounts(Request $request, $search=''){
		$accounts = DB::table('view_de_accounts_with_class');

		if(trim($search) != ''){
			$accounts = $accounts->where('account', 'like', '%'.$search.'%')->orWhere('code', 'like', '%'.$search.'%');
		}

		$accounts = $accounts->get();

		return json_decode($accounts);
	}

	public function update_gl_settings(Request $request){
		$gl_id = intval(trim($request->gl_id));

		$glSetting = $gl_id > 0 ? Setting::find($gl_id) : new Setting;

		$glSetting->payment_type = $request->payment_type;
		$glSetting->account_type = $request->account_type;
		$glSetting->income_ac_to_credit = $request->income_ac_to_credit;
		$glSetting->cash_book_ac_to_dedit = $request->cash_book_ac_to_dedit;
		$glSetting->debtors_acc = $request->debtors_acc;
		$glSetting->status = $request->status;
		$glSetting->company_id = company_id();
		$glSetting->status = $request->has('status');
		$glSetting->save();

		flash('GL Setting Updated Successfully!')->success();

		return redirect()->back();
	}

	public function toggle_setting_active(Request $request, $id){
		$setting = Setting::find($id);
		$setting->status = $request->status;
		$setting->save();

		return \json_encode(['status'=>true]);
	}

	public function recon_index(Request $request)
	{
		$recons = Recon::where('payload_type', '!=', 'student-verification');

		$otherStatus = "";

		if(!$request->has('other_status')){
			$recons = $recons->where('status', '<>', 'reconciled');
		}
		else{
			$recons = $recons->where('status', '=', $request->other_status);
			$otherStatus = $request->other_status;
		}

		$searchTerm = '';

		$start = date('Y-m-d\T00:00:00');
		$end = date('Y-m-d\T23:59:59');

		if($request->has('start') && $request->start!=""){
			$start = $request->start;
		}
		
		if($request->has('end') && $request->end!=""){
			$end = $request->end;			
			
		}

		$recons = $recons->where('created_at', '<=', $end);
		$recons = $recons->where('created_at', '>=', $start);

		if($request->has('search') && $request->search!=""){
			$searchTerm = $request->search;
			$recons = $recons->where('payload', 'like', '%'.$searchTerm.'%')->orWhere('bank', 'like', '%'.$searchTerm.'%');
		}

		if($request->has('sort')){
			$recons = $recons->orderBy($request->sort, $request->direction ?? 'asc');
		}

		$recons = $recons->paginate($request->has('limit') ? $request->limit : 25)->withQueryString();

		// return $start.' - '.$end;

		return $this->response('gl-posting-settings::reconciliation', compact('recons', 'searchTerm', 'start', 'end', 'otherStatus'));
	}

	public function posting_details(Request $request, $id){
		$recon = Recon::find($id);

		$isNewPayment = !isset($recon->id);

		$payload = $isNewPayment ? [] : json_decode($recon->payload);

		$bank_account = ($isNewPayment || (!isset($payload->AccountNr) && !isset($payload->account_number))) ? null : DB::table('accounts')->selectRaw('id, name, number, currency_code, bank_name')->where('number', ($payload->AccountNr ?? $payload->account_number))
			->orderBy('name', 'asc')->first();

		// return json_encode($bank_account);

		$bank_accounts = DB::table('accounts')->select('id', 'name', 'number', 'currency_code', 'bank_name')->orderBy('name', 'asc')->get();

		$invoice_ids = [];

		if(isset($recon->account_id) && trim($recon->account_id) == ""){
			$recon->account_id = $bank_account->id;
			$recon->save();
		}

		$CONTACT = !$isNewPayment && floatval($recon->contact_id) > 0 ? Contact::find($recon->contact_id)->toArray() : [];

		if(empty($CONTACT) && isset($payload->Narrative)){
			$CONTACT = Contact::where('reference', $payload->Narrative)->first() ?? [];

			if(!empty($CONTACT)){
				$recon->contact_id = $CONTACT->id;
				$recon->save();
			}
		}

		$balances = empty($CONTACT) ? [] : Contact::with('invoices.transactions')->customer()->where('contacts.id', $CONTACT['id'])->get();

		// return '<pre>'.json_encode($balances, JSON_PRETTY_PRINT);

		if(!empty($CONTACT) && isset($recon->id) && $recon->consensus_items_count() == 0){
			$this->distribute_payment_to_pending_invoices($recon, $CONTACT);
		}



		$accounts =  $isNewPayment ? [] : DB::table('double_entry_accounts')->where('id', $recon->de_account_id)
		->selectRaw('id, name, CONCAT(code, ":", name) as reference_str')->orderBy('name', 'asc')->limit(10)->get();

		if(!$isNewPayment){
			$isNewPayment = $recon->bank = "accounts_office";
		}

		$payment_methods = DB::table('settings')->where('key', 'offline-payments.methods')->select('value')->first();

		$payment_methods = json_decode($payment_methods->value, true);

		// return json_encode($payment_methods);

		return $this->response('gl-posting-settings::posting-ready', compact('recon', 'bank_account', 'bank_accounts', 'CONTACT', 'payload', 'accounts', 'isNewPayment', 'payment_methods'));
	}

	public function posting_details_update(Request $request, $id){
		$recon = Recon::find($id) ?? new Recon;

		if(isset($recon->id)){
			if($recon->status == 'reconciled'){
				flash('Changes can not be made! Payment already receipted!');

				return redirect()->back();
			}
		}

		$recon->receipt_date = \Carbon\Carbon::parse($request->receipted_on);
		$recon->transaction_time = \Carbon\Carbon::parse($request->paid_on);
		$recon->account_id = $request->account;
		$recon->de_account_id = $request->de_account_id;
		$recon->reference = $request->reference;
		$recon->mode_of_payment = $request->mode_of_payment;

		if(!isset($recon->id)){
			$payload = ['amount'=>0];
			$recon->bank = 'accounts_office';
			$recon->status = 'Pending';
			$recon->job_done = 0;
			$recon->payload_type = 'cash';
			foreach($request->all() as $k=>$v){
				$payload[$k] = $v;
			}

			$recon->payload = json_encode($payload);
		}

		$payload = json_decode($recon->payload);

		if(isset($payload->TransAmount) && floatval($payload->TransAmount) != floatval($request->amount_paid)){
			$payload->TransAmount = $request->amount_paid;
		}

		if(isset($payload->amount) && floatval($payload->amount) != floatval($request->amount_paid)){
			$payload->amount = $request->amount_paid;
		}

		$reconAmount = floatval($payload->TransAmount ?? ($payload->amount ?? 0));

		$recon->payload = json_encode($payload);

		if($recon->contact_id != $request->customer_id){
			$recon->contact_id = $request->customer_id;
			$recon->save();
			$contact = Contact::find($request->customer_id);
			$this->distribute_payment_to_pending_invoices($recon, $contact, true);
			// return "We are here";
			flash()->overlay("Customer changed successfully")->success();
				
			return redirect()->route('gl-posting-settings.get-posting-details', $recon->id);
		}

		$recon->save();

		$contact = Contact::find($recon->contact_id)->toArray();

		$rowsIDs = array_keys($request->row ?? []);

		Consensus::whereNotIn('id', $rowsIDs)->delete();

		$amountExceeds = 0;
		$itemAmounts = 0;

		foreach($request->row ?? [] as $key=>$iid){
			$item = Consensus::find($key)->toArray();
			$item = (object) $item;
			$item->account = $iid['account'] ?? NULL;
			$item->total = $iid['distributed_amount'];
			$item->amount_paid = $item->paid_amount;

			$itemAmounts += floatval($iid['distributed_amount']);

			$this->update_payment_distribution_row($item, 0, $item->id, $item->document_type, $contact['id'], $recon->id, $item->total);
		}

		$amountExceeds = $reconAmount - $itemAmounts;

		// return response()->json($request->new_row, 200);

		foreach($request->new_row ?? [] as $key=>$iid){
			$Item = Item::find($iid['item_id']);

			$item = (object)[
				'account' => $iid['account'] ?? NULL,
				'id' => $Item->id,
				'name' => $Item->name,
				'total' => $iid['distributed_amount'],
				'tax' => 0
			];

			$itemAmounts += floatval($iid['distributed_amount']);

			if(isset($iid['invoice_id'])){
				$document = Document::find($iid['invoice_id']);

				$paid_so_far = DB::table('payment_integration_consensuses')->select(DB::raw('SUM(COALESCE(payable_amount, 0)) as amount_paid'))
				->where('document_id', $document->id)->where('item_id', $Item->id)->where('fulfilment_status', '<>', 1)->first();

				$item->document_number = $document->document_number;
				$item->document_item_id = $iid['document_item_id'];
				$item->document_id = $document->id;
				$item->document_number = $document->document_number;
				$item->amount_paid = $paid_so_far->amount_paid ?? 0;
			}

			$this->update_payment_distribution_row($item, 0, 0, $item->document_type ?? 'invoice', $contact['id'], $recon->id, $item->total);
		}


		flash("Payment details updated successfully")->success();
			
		return redirect()->back();
	}

	public function fetch_de_accounts(Request $request, $search=''){
		$accounts =  \Modules\DoubleEntry\Models\Account::selectRaw('id, CONCAT(code, ":", name) as code_name');

		if(trim($search) != ''){
			$accounts = $accounts->where('name', 'like', '%'.$search.'%')->orWhere('code', 'like', '%'.$search.'%');
		}	

		$accounts = $accounts->get();

		return json_decode($accounts);
	}

	public function fetch_dist_items(Request $request, $contact_id, $searchTerm=''){
		$items = Document::join('document_items as di', function($join){
			$join->on('di.document_id', 'documents.id');
			$join->where('documents.type', 'invoice');
			$join->where('documents.status', '<>', 'paid');
			$join->where('documents.status', '<>', 'cancelled');
		})->join('items as i', 'i.id', 'di.item_id')
		->select('documents.document_number', 'i.name', 'di.id', 'di.item_id', 'di.document_id')->where('documents.contact_id', $contact_id)
		->where(function($where) use($searchTerm) {
			$where->where('i.name', 'LIKE', '%'.$searchTerm.'%')->orWhere('documents.document_number', 'LIKE', '%'.$searchTerm.'%');
		})->orderBy('documents.document_number', 'asc')->get();

		$otherItems = Item::where('name', 'like', '%'.$searchTerm.'%')->select('name', 'id')
		->where('sale_price', '>', 0)->orderBy('name', 'asc')->get();

		$theData = [];

		foreach($items as $i){
			$theData[] = (object)['name'=>$i->document_number.' - '.$i->name, 'id'=>$i->id.'-'.$i->item_id.'-'.$i->document_id];
		}

		foreach($otherItems as $i){
			$theData[] = $i;
		}

		return response()->json($theData, 200);
	}

	public function update_payment_distribution_row($item, $balance, $consensus_id=0, $docType, $contact_id, $recon_id, $adjusted_payable_amount=0){
		$itemTotal = floatval($item->tax ?? 0) + floatval($item->total ?? 0);
		$itemBalance = $itemTotal - floatval($item->amount_paid ?? 0);

		if($itemBalance > 0){
			//Payable amount will be equal to the balance if the amount due is equal to or greater than the balance available, otherwise the payable amount will be
				//equal to whatever is pending
			$payableAmount = $adjusted_payable_amount > 0 ? $adjusted_payable_amount : ($itemBalance >= $balance ? $balance : $itemBalance);
			$consensus = $consensus_id > 0 ? Consensus::find($consensus_id) : new Consensus();

			if($consensus_id == 0){
				$consensus->bank_integration_payload_id = $recon_id;
				$consensus->document_type = $docType;
				$consensus->document_number = $item->document_number ?? '-';
				$consensus->document_id = $item->document_id ?? NULL;
				$consensus->document_item_id = $item->document_item_id ?? NULL;
				$consensus->item_id = $item->id;
				$consensus->item_name = $item->name;
				$consensus->contact_id = $contact_id;
				$consensus->invoice_amount = $item->total;
				$consensus->invoice_amount_tax = $itemTotal;
				$consensus->paid_amount = $item->amount_paid ?? 0; //This is the amount paid so far for this specific item //true if amount paid is equal to the item balance
				$consensus->payment_id = 0;
			}

			$consensus->payable_amount = $payableAmount;
			$consensus->payable_account = $item->account;
			$consensus->fulfilment_status = $payableAmount == $itemBalance;
			$consensus->save();

			$balance = $payableAmount == $itemBalance ? ($balance - $payableAmount) : 0; 
		}

		return $balance;
	}

	public function distribute_payment_to_pending_invoices($recon, $contact, $flush=false){
		$payload = json_decode($recon->payload);

		$paidAmount = floatval($payload->TransAmount ?? $payload->amount);

		if($paidAmount == 0){
			return true;
		}

		$docType = 'invoice';

		$db_prefix = DB::getTablePrefix();

		$items = Document::join('document_items as di', function($join) use($docType){
			$join->on('di.document_id', 'documents.id');
			$join->where('documents.type', $docType);
			$join->whereNotIn('documents.status', ['paid', 'cancelled']);
		})->join('items as i', 'i.id', 'di.item_id')->leftJoin('payment_integration_consensuses as pic', function($join){
			$join->on('pic.document_id', 'documents.id');
			$join->on('pic.item_id', 'i.id');
			$join->where('pic.fulfilment_status', '<>', 1);
		})->where('documents.contact_id', $contact['id'])
		->select('documents.document_number', 'i.name', 'i.id', DB::raw($db_prefix . 'di.id as document_item_id'), 'di.document_id', 'di.quantity', 'di.tax', 'di.total', DB::raw('COALESCE('.$db_prefix.'pic.invoice_amount, 0) as invoice_amount'), 
		DB::raw('COALESCE('.$db_prefix.'pic.invoice_amount_tax, 0) as invoice_amount_tax'), DB::raw('SUM(COALESCE(payable_amount, 0)) as amount_paid'))
		->groupBy('i.id', 'documents.id')->orderBy('documents.created_at', 'desc')->orderBy('i.priority_level', 'desc')->get();

		$balance = $paidAmount;

		if($flush){
			Consensus::where('bank_integration_payload_id', $recon->id)->delete();
		}

		foreach($items as $item){
			if($balance > 0){
				$balance = $this->update_payment_distribution_row($item, $balance, 0, $docType, $contact['id'], $recon->id);
			}
			else{
				break;
			}
		}

		return true;
	}

	public function receipt_print($id){
		$receipt = GlPostingReceipt::find($id);

		// return json_encode($receipt);

		return view('gl-posting-settings::receipt', compact('receipt'));
	}

	public function post_payment(Request $request, $id){
		$recon = Recon::find($id);
		$consensus = Consensus::where('bank_integration_payload_id', $id)->get();

		$send_email_receipt = $request->send_receipt_to_customer ?? 0;

		$bank_account = DB::table('accounts')->selectRaw('id, name, number, currency_code, bank_name')->where('id', $recon->account_id)
			->orderBy('name', 'asc')->first();


		if(!isset($bank_account->id)){

			flash("Missing Bank Account")->error();
			return redirect()->back();
		}

		$payable_amnts = [];
		$TOTAL = 0;

		foreach($consensus as $con){
			$time = time();
			$keyID = $con->document_id ?? $time.$con->item_id;
			if(!isset($payable_amnts[$keyID])){
				
				$invoice_doc = Document::find($con->document_id);

				$payable_amnts[$keyID] = [
					'company_id' => company_id(),
					'type' => 'income',
					'account_id' => $bank_account->id,
					'paid_at' => $recon->transaction_time,
					'amount'=>0,
					'currency_code' => isset($invoice_doc->id) ? $invoice_doc->currency_code : ($bank_account->currency_code ?? 'KES'),
					'currency_rate' =>  $invoice_doc->currency_rate ?? 1,
					'document_id'=>$con->document_id ?? null,
					'contact_id'=>$recon->contact_id,
					'description' => '',
					'category_id' => 2,
					'payment_method' => $recon->mode_of_payment,
					'reference' => 'REF',
					'parent_id' => 0,
					'created_from' => 'core::ui',
					'created_by'=> \Auth::user()->id,
				];
			}

			if(!isset($invoice_doc->id)){
				$theITEM = Item::find($con->item_id);
				$payable_amnts[$keyID]['de_account_id'] = $theITEM->de_account()->id;
			}

			$payable_amnts[$keyID]['amount'] += floatval($con->payable_amount);
			$payable_amnts[$keyID]['description'] .= ",".$con->item_name;

			$con->paid_amount += floatval($con->payable_amount);
			$TOTAL += floatval($con->payable_amount);
			$con->save();
		}

		// return response()->json($payable_amnts, 200);

		foreach($payable_amnts as $id=>$arr){
			$myRequest = new TransRequest($arr);

			$hasDocument = Document::find($arr['document_id']);

			$controller = isset($hasDocument->id) ? new \App\Http\Controllers\Modals\DocumentTransactions() : new \App\Http\Controllers\Sales\Revenues();

			// return json_encode($controller);

			$create = !isset($hasDocument->id) ? $controller->store($myRequest) : $controller->store($hasDocument, $myRequest);

			// return json_encode($create);
		}

		$recon->status = 'reconciled';
		$recon->save();

		$previousReceipt = GlPostingReceipt::where('bank_integration_id', $recon->id)->first();
		$previousAmmendment = $previousReceipt->ammendment ?? null;

		if(isset($previousReceipt->id)){
			$previousReceipt->delete();
		}

		$receipt = new GlPostingReceipt;


		$receipt->company_id = company_id();
		$receipt->bank_integration_id = $recon->id;
		$receipt->amount = $TOTAL;

		if($previousAmmendment !== null){
			$receipt->ammendment = $previousAmmendment +1;
		}

		$CONTACT = Contact::find($recon->contact_id);

		$receipt->download_link = 'generating';
		$receipt->created_by = \Auth::user()->id;
		$receipt->contact_id = $recon->contact_id;
		$receipt->class = $CONTACT->class;
		$receipt->mode_of_payment = ucwords(explode('.',$recon->mode_of_payment)[1]);
		$receipt->currency = isset($invoice_doc->id) ? $invoice_doc->currency_code : ($bank_account->currency_code ?? 'KES');
		$receipt->number = 'SOON';
		$receipt->save();

		$receipt->number = 'ST-R'.str_pad($recon->id, 5,"0",STR_PAD_LEFT);
		$receipt->save();

		// return json_encode($receipt->creator);

		GlPostingReceiptJob::dispatchSync($receipt, $send_email_receipt);

		flash("Payment made.")->success();

		return redirect()->back();
	}

	public function caution_money(Request $request){ //for all customers
		$limit = (int) request('limit', setting('default.list_limit', '25'));
		$caution = DB::table('shear_caution_money as ca')->join('contacts as c', 'c.reference', 'ca.account_no');

		$start_date = '';
		$end_date = '';
		$customer = '';
		$filter_by = '';

		if($request->has('end_date') && trim($request->end_date) != ""){
			$caution = $caution->where('trans_date', '<=', $request->end_date);
			$end_date = $request->end_date;
		}

		if($request->has('start_date') && trim($request->start_date) != ""){
			$caution = $caution->where('trans_date', '>=', $request->start_date);
			$start_date = $request->start_date;
		}

		if($request->has('account_no') && trim($request->account_no) != ""){
			$caution = $caution->where('account_no', '=', $request->account_no);
			$customer = $request->account_no;
		}

		if($request->has('filter_by') && trim($request->filter_by) != ""){
			if($request->filter_by !='available_amnt'){
				$caution = $caution->where($request->filter_by, '>', 0);
			}
			else{
				$uniqueCaution = clone $caution;

				$uniqueCaution = $uniqueCaution->selectRaw('account_no, (SUM(amount_paid) - SUM(invoice_total)) as balance')
					->havingRaw('balance != 0')->groupBy('account_no')->pluck('account_no')->toArray();

				$uniqueCautionIDs = array_unique($uniqueCaution);

				$caution = $caution->where('amount_paid', '>', 0)->whereIn('account_no', $uniqueCautionIDs);
			}
			$filter_by = $request->filter_by;
		}
		

		$theContacts = Contact::where('type', 'customer')->select('reference','name', DB::raw("CONCAT(`name`, ' - ', COALESCE(`reference`, '0000')) as reference_str"))
			->orderBy('name', 'asc')->limit(10)->get();

		if($request->has('action')){
			if($request->action=="export"){
				$data = $caution->select(DB::raw('name as Name, account_no as `Admission Number`, entry_type as `Type`, trans_date as Date, invoice_no as `Invoice Number`, note as Note, invoice_total as Debit, amount_paid as Credit'))
					->get();

				$filename = 'Caution-Money-Export';

				return Excel::download(new ExportCustomerStatement($data), $filename.'.'.$request->format);
			}
		}


		$cautionTotals = clone $caution;

		$cautionTotals = $cautionTotals->selectRaw('SUM(invoice_total) as debit_total, SUM(amount_paid) as credit_total')->first();

		$caution = $caution->orderBy('ca.id', 'desc')->paginate($limit);

		return view('gl-posting-settings::sales.caution', compact('cautionTotals', 'theContacts', 'limit', 'caution', 'start_date', 'end_date', 'customer', 'filter_by'));
	}
	
	public function customer_statements(Request $request){ //for all customers
		$limit = (int) request('limit', setting('default.list_limit', '25'));
		$caution = ShearTransaction::join('contacts as c', 'c.reference', 'shear_transactions.account_no');

		$start_date = '';
		$end_date = '';
		$customer = '';
		$filter_by = '';

		if($request->has('account_no') && trim($request->account_no) != ""){
			$caution = $caution->where('account_no', '=', $request->account_no);

			$maxCaution = clone $caution;

			$maxCaution = $maxCaution->select(DB::raw("MIN(trans_date) as min, MAX(trans_date) as max"))->first();

			$customer = $request->account_no;

			$start_date = $maxCaution->min;
			$end_date = $maxCaution->max;
		}

		$balanceBF = clone $caution;

		if($request->has('end_date') && trim($request->end_date) != ""){
			$caution = $caution->where('trans_date', '<=', $request->end_date);
			$end_date = $request->end_date;
		}

		if($request->has('start_date') && trim($request->start_date) != ""){
			$caution = $caution->where('trans_date', '>=', $request->start_date);
			$start_date = $request->start_date;
		}

		if($request->has('filter_by') && trim($request->filter_by) != ""){
			$caution = $caution->where($request->filter_by, '>', 0);
			$filter_by = $request->filter_by;
		}

		$theContacts = Contact::where('type', 'customer')->select('reference','name', DB::raw("CONCAT(`name`, ' - ', COALESCE(`reference`, '0000')) as reference_str"))
			->orderBy('name', 'asc')->limit(10)->get();

		$ageAnalysis = AgeAnalysisBand::orderBy('lower', 'asc')->get();

		foreach($ageAnalysis as $aA){
			$aCaution = clone $caution;
			$dateLower = Carbon::today()->subDays($aA->lower);
			$dateUpper = Carbon::today()->subDays($aA->upper);
			$ageAnalysisData = $aCaution->where('trans_date', '<=', $dateLower)->where('trans_date', '>=', $dateUpper)->selectRaw('SUM(invoice_total-amount_paid) as balance')->first();
			$aA->value = $ageAnalysisData->balance ?? 0;
		}

		$balanceBFTT = $balanceBF->where('trans_date', '<', $start_date)->selectRaw('SUM(invoice_total) as debit_total, SUM(amount_paid) as credit_total')->first();

		if($request->has('action')){
			$data = $caution->select(DB::raw('name as Name, account_no as `Admission Number`, entry_type as `Type`, trans_date as Date, invoice_no as `Invoice Number`, note as Note, invoice_total as Debit, amount_paid as Credit'))
				->orderBy('shear_transactions.id', 'desc')->get();
			if($request->action=="export"){
				$filename = 'Customer-Statement-Export';

				return Excel::download(new ExportCustomerStatement($data), $filename.'.'.$request->format);
			}

			if($request->action=="print"){
				$customer = Contact::where('reference', $customer)->first();
				$data = $this->calculate_line_balances($data);

				// return response()->json($data, 200);

				return view('gl-posting-settings::sales.customer-statement-print', compact('balanceBFTT', 'ageAnalysis', 'data', 'customer', 'start_date', 'end_date'));
			}
		}

		$cautionTotals = clone $caution;

		$cautionTotals = $cautionTotals->selectRaw('SUM(invoice_total) as debit_total, SUM(amount_paid) as credit_total')->first();

		$caution = $caution->orderBy('shear_transactions.id', 'asc')->paginate($limit);

		// $caution = $this->calculate_line_balances($caution);

		// return response()->json($caution->items(), 200);

		return view('gl-posting-settings::sales.customer-statement', compact('balanceBFTT', 'ageAnalysis', 'cautionTotals', 'theContacts', 'limit', 'caution', 'start_date', 'end_date', 'customer', 'filter_by'));
	}

	public function calculate_line_balances($caution){
		$newData = [];
		$creditBalance = 0;
		$debitBalance = 0;
		foreach($caution as $row){
			$creditBalance += floatval($row->amount_paid ?? $row->Credit);
			$debitBalance += floatval($row->invoice_total ?? $row->Debit);
			$rowBalance = $debitBalance - $creditBalance;

			$row->row_balance = $rowBalance;
			$newData[] = $row;
		}

		return $newData;
	}

	public function customer_statement_print(Request $request, $id){
		
	}

	public function add_age_band(Request $request){
		$band = AgeAnalysisBand::where('lower', $request->lower)->where('upper', $request->upper)->first();

		if(!isset($band->id)){
			$band = new AgeAnalysisBand;
		}		
		$band->company_id = company_id();
		$band->lower = $request->lower;
		$band->upper = $request->upper;
		$band->title = $request->title;

		$band->save();

		return redirect()->back();
	}

	public function delete_age_band($id){
		$band = AgeAnalysisBand::findOrFail($id);

		$band->delete();
		return redirect()->back();
	}

	public function credit_debit_note_approval(Request $request, $id){
		$model = Document::class;
		$module = \Module::find('credit-debit-notes');

		$document = Document::find($id);

		$request->request->add(['model'=>$model]);
		$request->request->add(['reference'=>$document->document_number]);
		$request->request->add(['model_id'=>$id]);
		$request->request->add(['module'=>$module->getName()]);
		$request->request->add(['resource_link'=>route('credit-debit-notes.'.$document->type.'s.show', $id)]);

		$ApprovalController = new \Modules\SystemApprovals\Http\Controllers\Main();
		$generate_approvals = $ApprovalController->create_approval($request, true);

		$document->status = "awaiting_approval";
		$document->save();
		// return $generate_approvals;

		if($generate_approvals){
			return redirect()->back()->with('success', 'Approvals created successfully.');
		}
		else{
			return redirect()->back()->with('error', 'Approval generation failed! Please confirm that there are approvals configured for Credit Debit.');
		}
	}
}