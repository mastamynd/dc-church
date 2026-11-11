<?php

namespace Modules\PaymentReception\Services;

use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Document\Document;
use App\Models\Document\DocumentItem;
use App\Models\Document\DocumentTotal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Church\Models\Church;
use Modules\Church\Models\PaymentDivision;
use Modules\Demarcations\Models\DemarcationHistory;
use Modules\PaymentReception\Models\PaymentReception;

class PaymentPoster
{
	protected $context;

	public function postMany(array $ids): array
	{
		$ids = array_values(array_unique(array_filter($ids, function ($id) {
			return is_numeric($id) && (int) $id > 0;
		})));

		if ($ids === []) {
			return [
				'posted' => 0,
				'skipped' => 0,
				'issues' => [],
				'message' => 'No payments were selected.',
				'level' => 'error',
			];
		}

		$this->prepareRuntime();

		$payments = PaymentReception::query()
			->with([
				'items',
				'church.subregion',
				'church.pastors.pastor.contact',
			])
			->whereIn('id', $ids)
			->get()
			->keyBy('id');

		$existingInvoices = Document::query()
			->whereIn('parent_id', $ids)
			->pluck('parent_id')
			->flip();

		$this->context = $this->makeContext($payments, true);

		$posted = 0;
		$skipped = 0;
		$issues = [];

		try {
			foreach ($ids as $id) {
				$payment = $payments->get($id);

				if (!$payment) {
					$skipped++;
					$issues[] = 'Payment #'.$id.' was not found.';
					continue;
				}

				$result = $this->postPayment($payment, $existingInvoices->has($id));

				if ($result === true) {
					$posted++;
					$existingInvoices->put($payment->id, true);
					continue;
				}

				$skipped++;
				if (is_string($result) && $result !== '') {
					$issues[] = $result;
				}
			}
		} finally {
			$this->persistInvoiceNumber();
		}

		$message = 'Posted '.$posted.' of '.count($ids).' payment(s).';
		if ($skipped > 0) {
			$message .= ' '.$skipped.' skipped.';
		}
		if ($issues) {
			$message .= ' '.implode(' ', array_slice($issues, 0, 8));
			if (count($issues) > 8) {
				$message .= ' (+'.(count($issues) - 8).' more)';
			}
		}

		return [
			'posted' => $posted,
			'skipped' => $skipped,
			'issues' => $issues,
			'message' => $message,
			'level' => $posted > 0 ? 'success' : 'error',
		];
	}

	public function postOne($id, $internal = false)
	{
		$this->prepareRuntime();

		$payment = PaymentReception::query()
			->with([
				'items',
				'church.subregion',
				'church.pastors.pastor.contact',
			])
			->find($id);

		if (!$payment) {
			if ($internal) {
				return 'Payment #'.$id.' was not found.';
			}

			flash('Payment was not found!')->important()->error();
			return redirect()->route('payment-reception.index');
		}

		$hasInvoice = Document::query()->where('parent_id', $payment->id)->exists();
		$this->context = $this->makeContext(collect([$payment]), false);

		try {
			$result = $this->postPayment($payment, $hasInvoice);
		} finally {
			$this->persistInvoiceNumber();
		}

		if ($internal) {
			return $result;
		}

		if ($result === true) {
			flash('Tithe Submission Approved!')->success();
			return redirect()->route('payment-reception.view-tithe', $id);
		}

		flash($result)->important()->error();
		return redirect()->route('payment-reception.view-tithe', $id);
	}

	protected function postPayment(PaymentReception $payment, $hasInvoice)
	{
		$church = $payment->church;

		if (!$church) {
			$payment->status = 'pending_issue';
			$payment->save();
			return 'Payment #'.$payment->id.' has no church.';
		}

		if ($payment->total <= 0) {
			$payment->status = 'pending_issue';
			$payment->save();
			return 'Payment for '.$church->name.' has no value!';
		}

		if (!isset($church->subregion->church_id)) {
			$payment->status = 'pending_issue';
			$payment->save();
			return 'No Sub-Region has been assigned to the church '.$church->name.'!';
		}

		$pastors = $church->pastors;

		if ($pastors->count() == 0) {
			$payment->status = 'pending_issue';
			$payment->save();
			return 'No Pastors add to the church '.$church->name.'!';
		}

		foreach ($pastors as $pastor) {
			if (in_array($pastor->rank, ['Select Rank...'])) {
				$payment->status = 'pending_issue';
				$payment->save();
				return 'Pastor`s rank not set in church '.$church->name.'!';
			}
		}

		$seniorCount = $church->pastors->where('position', 'Senior')->count();

		if ($seniorCount == 1) {
			$payment->status = 'pending_issue';
			$payment->save();
			return 'Church '.$church->name.' missing Senior Pastor Details!';
		}

		if ($seniorCount > 1) {
			$payment->status = 'pending_issue';
			$payment->save();
			return 'Church '.$church->name.' more than 1 Senior Pastor configured!';
		}

		if ($payment->status == 'Completed' || $hasInvoice) {
			if ($payment->status != 'Completed') {
				$payment->status = 'Completed';
				$payment->save();
			}

			return 'Tithe has already been submitted!';
		}

		DB::transaction(function () use ($payment) {
			$this->createInvoice($payment);
			$payment->status = 'Completed';
			$payment->save();
		});

		return true;
	}

	public function createInvoice(PaymentReception $payment)
	{
		$ctx = $this->context ?: $this->makeContext(collect([$payment]), false);

		if (!$ctx->contact) {
			throw new \RuntimeException('Payment reception contact is not configured.');
		}

		$church = $payment->church ?: Church::with(['subregion', 'pastors.pastor.contact'])->find($payment->church_id);
		if ($church && !$church->relationLoaded('pastors')) {
			$church->load(['subregion', 'pastors.pastor.contact']);
		}
		$items = $payment->relationLoaded('items') ? $payment->items : $payment->items()->get();
		$invoiceItems = $ctx->invoiceItems;
		$contact = $ctx->contact;
		$subRegionId = isset($church->subregion) ? (int) $church->subregion->demarcation_id : 0;
		$regionId = $ctx->regionBySubregion[$subRegionId] ?? 0;
		$now = $ctx->now;
		$userId = $ctx->userId;
		$companyId = $ctx->companyId;
		$splitCriteria = [
			1 => ['Senior' => 100],
			2 => ['Senior' => 60, 'Assistant' => 40],
		];

		foreach ($items as $itm) {
			$documentNumber = 'INV-'.str_pad($ctx->nextInvoiceNumber, 5, '0', STR_PAD_LEFT);
			$invoiceTotal = floatval($itm->amount);
			$subTotal = 0;

			$document = Document::create([
				'contact_id' => $contact->id,
				'company_id' => $companyId,
				'currency_code' => 'KES',
				'currency_rate' => 1,
				'contact_country' => 'KE',
				'contact_name' => 'System Portal',
				'contact_email' => $contact->email,
				'parent_id' => $payment->id,
				'status' => 'draft',
				'type' => 'invoice',
				'document_number' => $documentNumber,
				'created_by' => $userId,
				'created_at' => $now,
				'issued_at' => $now,
				'due_at' => $now,
				'updated_at' => $now,
				'amount' => 0,
			]);

			$ctx->nextInvoiceNumber++;
			$ctx->dirtyInvoiceNumber = true;

			foreach ($invoiceItems as $rowD) {
				$distributedAmount = $invoiceTotal * (floatval($rowD['percentage']) / 100);

				if ($rowD['code'] == 'pastors_fund') {
					$theTopUp = floatval($itm->topup ?? 0);
					$pastors = $church->pastors ?? collect();
					$noCount = count($pastors);
					$split = $splitCriteria[$noCount] ?? ['Senior' => 40, 'Assistant' => 60];
					$splitAmounts = [];
					$topUpAmounts = [];

					foreach ($split as $s => $p) {
						$splitAmounts[$s] = $p / 100 * $distributedAmount;
						$topUpAmounts[$s] = $theTopUp != 0 ? $p / 100 * $theTopUp : 0;
					}

					foreach ($pastors as $pastor) {
						$pastorModel = $pastor->pastor;
						$pastorItemName = $pastor->position.' - '.($pastorModel->contact->name ?? '');
						$pastorDescription = 'pastor-'.$pastor->pastor_id;
						$pastorItem = $this->itemForSku($pastorDescription, $pastorItemName, $ctx);

						$paymentBracketAmount = $splitAmounts[$pastor->rank];
						$pTopUpBracketAmount = $topUpAmounts[$pastor->rank];

						if ($noCount == 1) {
							$price = $distributedAmount;
							$pTopUp = $theTopUp;
						} elseif ($pastor->rank == 'Senior') {
							$price = $paymentBracketAmount;
							$pTopUp = $pTopUpBracketAmount;
						} else {
							$otherRanks = $noCount - 1;
							$price = $paymentBracketAmount / $otherRanks;
							$pTopUp = $pTopUpBracketAmount / $otherRanks;
						}

						$itemArrayData = [
							'company_id' => $companyId,
							'item_id' => $pastorItem->id,
							'name' => $pastorItem->name,
							'description' => $pastorItem->description,
							'type' => 'invoice',
							'price' => $price,
							'topup' => $pTopUp,
							'total' => $price,
							'quantity' => 1,
							'document_id' => $document->id,
							'updated_at' => $now,
							'created_by' => $userId,
							'created_at' => $now,
							'de_account_id' => $ctx->salesAccountId,
						];

						$newDocItem = $this->createDocumentItem($itemArrayData, $document);
						$this->createPaymentDivision([
							'church_id' => $payment->church_id,
							'regional_id' => $regionId,
							'sub_regional_id' => $subRegionId,
							'contact_id' => $pastor->pastor_id,
							'type' => $rowD['code'],
							'amount' => $price,
							'topup' => $pTopUp,
							'invoice_id' => $document->id,
							'invoice_item_id' => $newDocItem->id,
							'year' => $itm->year,
							'month' => $itm->month,
							$itm->month => $price,
						]);

						$subTotal += floatval($itemArrayData['total']);
					}
				} else {
					$distributionItem = $this->itemForSku($rowD['code'], $rowD['name'], $ctx);

					$itemArrayData = [
						'company_id' => $companyId,
						'item_id' => $distributionItem->id,
						'name' => $distributionItem->name,
						'description' => $distributionItem->description,
						'type' => 'invoice',
						'quantity' => 1,
						'price' => $distributedAmount,
						'total' => $distributedAmount,
						'document_id' => $document->id,
						'updated_at' => $now,
						'created_by' => $userId,
						'created_at' => $now,
						'de_account_id' => $ctx->salesAccountId,
					];

					$newDocItem = $this->createDocumentItem($itemArrayData, $document);
					$this->createPaymentDivision([
						'church_id' => $payment->church_id,
						'regional_id' => $regionId,
						'sub_regional_id' => $subRegionId,
						'contact_id' => 0,
						'type' => $rowD['code'],
						'amount' => $distributedAmount,
						'invoice_id' => $document->id,
						'invoice_item_id' => $newDocItem->id,
						'year' => $itm->year,
						'month' => $itm->month,
						$itm->month => $distributedAmount,
					]);

					$subTotal += floatval($itemArrayData['total']);
				}
			}

			foreach (['sub_total', 'total'] as $index => $code) {
				$this->createDocumentTotal([
					'company_id' => $companyId,
					'type' => 'invoice',
					'document_id' => $document->id,
					'code' => $code,
					'name' => 'invoices.'.$code,
					'amount' => $subTotal,
					'sort_order' => $index + 1,
					'created_from' => 'core::ui',
					'created_by' => $userId,
				], $document);
			}

			$document->amount = $subTotal;
			$document->setRelation('transactions', collect());
			$document->save();

			try {
				event(new \App\Events\Document\PaymentReceived($document, ['type' => 'income', 'mark_paid' => 'invoice']));
			} catch (\Exception $e) {
				if (!$ctx->bulk) {
					flash($e->getMessage())->error()->important();
				}
			}
		}

		if (!$ctx->bulk) {
			$this->persistInvoiceNumber();
		}

		return true;
	}

	protected function createDocumentItem(array $data, Document $document): DocumentItem
	{
		$item = new DocumentItem($data);
		$item->setRelation('document', $document);
		$item->save();

		return $item;
	}

	protected function createDocumentTotal(array $data, Document $document): DocumentTotal
	{
		return DocumentTotal::withoutEvents(function () use ($data, $document) {
			$total = new DocumentTotal($data);
			$total->setRelation('document', $document);
			$total->save();

			return $total;
		});
	}

	protected function createPaymentDivision(array $data): PaymentDivision
	{
		return PaymentDivision::create($data);
	}

	protected function itemForSku($sku, $name, $ctx): Item
	{
		if (isset($ctx->itemsBySku[$sku])) {
			return $ctx->itemsBySku[$sku];
		}

		$item = Item::create([
			'company_id' => $ctx->companyId,
			'name' => $name,
			'sku' => $sku,
			'description' => $name,
			'quantity' => 1,
			'sale_price' => 0,
			'purchase_price' => 0,
			'created_from' => 'core::ui',
			'created_by' => $ctx->userId,
		]);

		$ctx->itemsBySku[$sku] = $item;

		return $item;
	}

	protected function makeContext($payments, $bulk)
	{
		$invoiceItems = json_decode(setting('church.payment_divisions'), true) ?: [];
		$subRegionIds = $payments->map(function ($payment) {
			return $payment->church->subregion->demarcation_id ?? null;
		})->filter()->unique()->values();

		$regionBySubregion = [];
		if ($subRegionIds->isNotEmpty()) {
			$regionBySubregion = DemarcationHistory::query()
				->whereIn('demarcation_id', $subRegionIds->all())
				->orderByDesc('id')
				->get(['demarcation_id', 'parent_id'])
				->unique('demarcation_id')
				->pluck('parent_id', 'demarcation_id')
				->all();
		}

		$skus = collect($invoiceItems)->pluck('code')->filter();
		$pastorSkus = $payments->flatMap(function ($payment) {
			if (!$payment->church || !$payment->church->relationLoaded('pastors')) {
				return collect();
			}

			return $payment->church->pastors->pluck('pastor_id');
		})->filter()->unique()->map(function ($id) {
			return 'pastor-'.$id;
		});

		$itemSkus = $skus->merge($pastorSkus)->unique()->values();
		$itemsBySku = $itemSkus->isEmpty()
			? collect()
			: Item::query()->whereIn('sku', $itemSkus->all())->get()->keyBy('sku');

		$salesAccountId = null;
		if (class_exists(\Modules\DoubleEntry\Models\Account::class)) {
			$salesAccountId = \Modules\DoubleEntry\Models\Account::code(setting('double-entry.accounts_sales', 400))->value('id');
		}

		$ctx = new \stdClass();
		$ctx->bulk = $bulk;
		$ctx->contact = Contact::customer()->where('email', 'payment-reception@deliverancechurch.international')->first();
		$ctx->invoiceItems = $invoiceItems;
		$ctx->itemsBySku = $itemsBySku;
		$ctx->nextInvoiceNumber = (int) setting('invoice.number_next');
		$ctx->dirtyInvoiceNumber = false;
		$ctx->companyId = company_id();
		$ctx->userId = auth()->id();
		$ctx->now = Carbon::now();
		$ctx->regionBySubregion = $regionBySubregion;
		$ctx->salesAccountId = $salesAccountId;

		return $ctx;
	}

	protected function persistInvoiceNumber()
	{
		if (!$this->context || empty($this->context->dirtyInvoiceNumber)) {
			return;
		}

		setting(['invoice.number_next' => $this->context->nextInvoiceNumber])->save();
		$this->context->dirtyInvoiceNumber = false;
	}

	protected function prepareRuntime()
	{
		@set_time_limit(0);
		ini_set('max_execution_time', '0');
		ini_set('memory_limit', '1024M');
		DB::connection()->disableQueryLog();
		config(['laravel-model-caching.enabled' => false]);
	}
}
