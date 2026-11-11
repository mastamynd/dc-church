<?php

namespace App\Http\Livewire\MakePayments;

use Carbon\Carbon;
use Livewire\Component;
use Modules\Church\Models\PaymentDivision;

class Index extends Component
{
    public $page1;

    public $page2;

    public $pageTitle;

    public $region;

    public $activeType;

    public $lists = [];

    /** @var array<string, array<int, true>> groupKey => [id => true] for unchecked rows */
    public $unchecked = [];

    /** @var array<string, true> groups where every row is unchecked */
    public $uncheckedAll = [];

    public $hasPayAll = false;

    public $hasPaySub = false;

    public $hasPayReg = false;

    public $layer2 = ['region', 'sub_region'];

    public $grandTotal = 0;

    public $showErrors = false;

    public $accordion = [];

    /**
     * Region/subregion summary tree without line items.
     * [region => ['total' => float, 'items' => [sub => ['total' => float]]]]
     */
    public $breakdown = [];

    /** @var array<int, float> */
    public $rowAmounts = [];

    /** @var array<string, array<int>> */
    public $groupItemIds = [];

    /** Loaded line items keyed by groupKey */
    public $loadedGroups = [];

    public $toggleAll = [];

    public $chequeNumbers = [];

    public $pay_date;

    public $errors_there = [];

    public $showBankExportModal = false;

    public $bankExportType;

    public $bankExportPaymentDetails;

    public function mount($region)
    {
        $this->region = $region == null || trim((string) $region) === '' ? null : $region;
        $this->bankExportPaymentDetails = Carbon::now()->format('F Y') . ' EFT';
        $this->getTypes();
    }

    public function openBankExport($type)
    {
        if (!in_array($type, ['region', 'sub_region'], true)) {
            return;
        }

        $this->bankExportType = $type;
        if (trim((string) $this->bankExportPaymentDetails) === '') {
            $this->bankExportPaymentDetails = Carbon::now()->format('F Y') . ' EFT';
        }
        $this->showBankExportModal = true;
    }

    public function closeBankExport()
    {
        $this->showBankExportModal = false;
        $this->bankExportType = null;
    }

    public function downloadBankExport()
    {
        if (!in_array($this->bankExportType, ['region', 'sub_region'], true)) {
            return;
        }

        $params = [
            'type' => $this->bankExportType,
            'payment_details' => trim((string) $this->bankExportPaymentDetails) !== ''
                ? trim((string) $this->bankExportPaymentDetails)
                : (Carbon::now()->format('F Y') . ' EFT'),
        ];

        if ($this->region) {
            $params['region'] = $this->region;
        }

        $this->showBankExportModal = false;

        return redirect()->route('demarcations.payments.bank-export', $params);
    }

    public function showType($d)
    {
        $this->resetPaymentWorkspace();
        $this->page1 = true;
        $this->pageTitle = ucwords($d);
        $this->activeType = $d;

        if (in_array($d, $this->layer2, true)) {
            $this->hasPayReg = $d === 'region';
            $this->hasPaySub = $d === 'sub_region';
        } else {
            $this->hasPayAll = true;
        }

        $query = PaymentDivision::query()
            ->select([
                'payment_divisions.id',
                'payment_divisions.amount',
                'payment_divisions.topup',
            ])
            ->join('demarcations as regions', function ($join) {
                $join->on('regions.id', '=', 'payment_divisions.regional_id')
                    ->whereNull('regions.deleted_at');
            })
            ->leftJoin('demarcations as subregions', function ($join) {
                $join->on('subregions.id', '=', 'payment_divisions.sub_regional_id')
                    ->whereNull('subregions.deleted_at');
            })
            ->addSelect([
                'regions.name as region_name',
                'subregions.name as subregion_name',
            ])
            ->whereNull('payment_divisions.payment_date')
            ->where('payment_divisions.type', $d)
            ->orderBy('regions.name')
            ->orderBy('subregions.name')
            ->orderBy('payment_divisions.id');

        if ($this->region) {
            $query->where('payment_divisions.regional_id', $this->region);
        }

        $theData = [];
        $grandTotal = 0.0;

        foreach ($query->cursor() as $br) {
            $regionName = $br->region_name;
            if ($regionName === null || $regionName === '') {
                continue;
            }

            $subName = $br->subregion_name ?: 'All';
            $groupKey = $regionName . '|' . $subName;
            $amount = round((float) $br->amount + (float) $br->topup, 1);
            $id = (int) $br->id;

            if (!isset($theData[$regionName])) {
                $this->accordion[$regionName . '-region'] = false;
                $theData[$regionName] = ['total' => 0.0, 'items' => []];
            }

            if (!isset($theData[$regionName]['items'][$subName])) {
                $this->accordion[$groupKey . '-subregion'] = false;
                $this->unchecked[$groupKey] = [];
                $this->toggleAll[$groupKey] = true;
                $this->groupItemIds[$groupKey] = [];
                $theData[$regionName]['items'][$subName] = ['total' => 0.0];
            }

            $theData[$regionName]['items'][$subName]['total'] += $amount;
            $theData[$regionName]['total'] += $amount;
            $grandTotal += $amount;

            $this->rowAmounts[$id] = $amount;
            $this->groupItemIds[$groupKey][] = $id;
        }

        foreach ($theData as $regionName => $regionData) {
            $theData[$regionName]['total'] = round($regionData['total'], 1);
            foreach ($regionData['items'] as $subName => $subData) {
                $theData[$regionName]['items'][$subName]['total'] = round($subData['total'], 1);
            }
        }

        $this->breakdown = $theData;
        $this->grandTotal = round($grandTotal, 1);
    }

    public function calculateTotals()
    {
        $grandTotal = 0.0;

        foreach ($this->breakdown as $regionName => $regionData) {
            $regionTotal = 0.0;

            foreach ($regionData['items'] as $subName => $subData) {
                $groupKey = $regionName . '|' . $subName;
                $subTotal = 0.0;
                $unchecked = $this->unchecked[$groupKey] ?? [];

                if (!empty($this->uncheckedAll[$groupKey])) {
                    $subTotal = 0.0;
                } else {
                    foreach ($this->groupItemIds[$groupKey] ?? [] as $id) {
                        if (empty($unchecked[$id])) {
                            $subTotal += $this->rowAmounts[$id] ?? 0;
                        }
                    }
                }

                $subTotal = round($subTotal, 1);
                $this->breakdown[$regionName]['items'][$subName]['total'] = $subTotal;
                $regionTotal += $subTotal;
            }

            $regionTotal = round($regionTotal, 1);
            $this->breakdown[$regionName]['total'] = $regionTotal;
            $grandTotal += $regionTotal;
        }

        $this->grandTotal = round($grandTotal, 1);
    }

    public function backToLists()
    {
        $this->resetPaymentWorkspace();
        $this->page1 = null;
        $this->page2 = null;
        $this->getTypes();
    }

    public function getTypes()
    {
        $divisions = json_decode(setting('church.payment_divisions'), true) ?: [];
        $this->lists = [];

        foreach ($divisions as $v) {
            if (!isset($v['code'])) {
                continue;
            }
            $this->lists[$v['code']] = $v;
            $this->lists[$v['code']]['amount'] = 0;
        }

        $paymentsList = PaymentDivision::query()
            ->selectRaw('SUM(amount + topup) as total, type')
            ->whereNull('payment_date');

        if ($this->region) {
            $paymentsList->where('regional_id', $this->region);
        }

        foreach ($paymentsList->groupBy('type')->get() as $list) {
            if (!isset($this->lists[$list->type])) {
                continue;
            }
            $this->lists[$list->type]['amount'] = $list->total;
        }
    }

    public function makePayment()
    {
        if (trim((string) $this->pay_date) === '') {
            $this->errors_there['pay_date'] = true;
            $this->showErrors = true;

            return;
        }

        $this->errors_there['pay_date'] = false;

        $cheques = [];
        foreach ($this->chequeNumbers as $c => $d) {
            if (trim((string) $d) !== '') {
                $cheques[$c] = trim((string) $d);
            }
        }

        if (count($cheques) === 0) {
            $this->errors_there['cheques'] = true;
            $this->showErrors = true;

            return;
        }

        $this->errors_there['cheques'] = false;
        $this->showErrors = false;

        $paymentDate = Carbon::parse($this->pay_date)->startOfDay();

        foreach ($cheques as $c => $theChequeNumber) {
            $getIDs = $this->selectedIdsForChequeKey($c);

            if ($getIDs === []) {
                continue;
            }

            foreach (array_chunk($getIDs, 500) as $chunk) {
                PaymentDivision::whereIn('id', $chunk)->update([
                    'payment_date' => $paymentDate,
                    'bill_id' => 10,
                    'bill_item_id' => 10,
                    'cheque_number' => $theChequeNumber,
                ]);
            }
        }

        $this->backToLists();
    }

    protected function selectedIdsForChequeKey(string $key): array
    {
        $ids = [];

        if ($key === 'All' || $this->hasPayAll) {
            foreach ($this->groupItemIds as $groupKey => $groupIds) {
                if (!empty($this->uncheckedAll[$groupKey])) {
                    continue;
                }
                $unchecked = $this->unchecked[$groupKey] ?? [];
                foreach ($groupIds as $id) {
                    if (empty($unchecked[$id])) {
                        $ids[] = $id;
                    }
                }
            }

            return array_values(array_unique($ids));
        }

        if ($this->hasPayReg && isset($this->breakdown[$key])) {
            foreach ($this->breakdown[$key]['items'] as $subName => $subData) {
                $groupKey = $key . '|' . $subName;
                if (!empty($this->uncheckedAll[$groupKey])) {
                    continue;
                }
                $unchecked = $this->unchecked[$groupKey] ?? [];
                foreach ($this->groupItemIds[$groupKey] ?? [] as $id) {
                    if (empty($unchecked[$id])) {
                        $ids[] = $id;
                    }
                }
            }

            return array_values(array_unique($ids));
        }

        if ($this->hasPaySub) {
            if (!empty($this->uncheckedAll[$key])) {
                return [];
            }
            $unchecked = $this->unchecked[$key] ?? [];
            foreach ($this->groupItemIds[$key] ?? [] as $id) {
                if (empty($unchecked[$id])) {
                    $ids[] = $id;
                }
            }
        }

        return array_values(array_unique($ids));
    }

    public function toggleAccordion($id)
    {
        $open = !($this->accordion[$id] ?? false);
        $this->accordion[$id] = $open;

        if (str_ends_with($id, '-subregion')) {
            $groupKey = substr($id, 0, -strlen('-subregion'));
            if ($open) {
                $this->loadGroupItems($groupKey);
            } else {
                unset($this->loadedGroups[$groupKey]);
            }
        }
    }

    protected function loadGroupItems(string $groupKey): void
    {
        if (isset($this->loadedGroups[$groupKey])) {
            return;
        }

        $ids = $this->groupItemIds[$groupKey] ?? [];
        if ($ids === []) {
            $this->loadedGroups[$groupKey] = [];

            return;
        }

        [$regionName, $subName] = array_pad(explode('|', $groupKey, 2), 2, 'All');

        $rows = [];
        foreach (array_chunk($ids, 500) as $chunk) {
            $chunkRows = PaymentDivision::query()
                ->select([
                    'id',
                    'church_id',
                    'contact_id',
                    'amount',
                    'topup',
                    'month',
                    'year',
                ])
                ->with([
                    'church:id,name',
                    'pastor:id,contact_id',
                    'pastor.contact:id,name',
                ])
                ->whereIn('id', $chunk)
                ->orderBy('church_id')
                ->orderBy('contact_id')
                ->get();

            foreach ($chunkRows as $br) {
                $rows[] = [
                    'id' => $br->id,
                    'amount' => (float) $br->amount,
                    'topup' => (float) ($br->topup ?? 0),
                    'month' => $br->month,
                    'year' => $br->year,
                    'church' => $br->church->name ?? 'n/a',
                    'pastor' => $br->pastor->contact->name ?? 'n/a',
                    'region' => $regionName,
                    'subregion' => $subName,
                ];
            }
        }

        $this->loadedGroups[$groupKey] = $rows;
    }

    public function toggleSelected($sub, $id = false, $action = 'true')
    {
        if ($id) {
            $id = (int) $id;

            if (!empty($this->uncheckedAll[$sub])) {
                // Was fully unchecked: start from all unchecked, then check this one
                $this->uncheckedAll[$sub] = false;
                unset($this->uncheckedAll[$sub]);
                $this->unchecked[$sub] = [];
                foreach ($this->groupItemIds[$sub] ?? [] as $rowId) {
                    if ($rowId !== $id) {
                        $this->unchecked[$sub][$rowId] = true;
                    }
                }
            } elseif (!empty($this->unchecked[$sub][$id])) {
                unset($this->unchecked[$sub][$id]);
            } else {
                $this->unchecked[$sub][$id] = true;
            }

            $total = count($this->groupItemIds[$sub] ?? []);
            $uncheckedCount = count($this->unchecked[$sub] ?? []);

            if ($uncheckedCount >= $total && $total > 0) {
                $this->uncheckedAll[$sub] = true;
                $this->unchecked[$sub] = [];
                $this->toggleAll[$sub] = false;
            } else {
                unset($this->uncheckedAll[$sub]);
                $this->toggleAll[$sub] = $uncheckedCount === 0;
            }
        } else {
            $selectAll = !($action === 'true');
            $this->toggleAll[$sub] = $selectAll;
            $this->unchecked[$sub] = [];

            if ($selectAll) {
                unset($this->uncheckedAll[$sub]);
            } else {
                $this->uncheckedAll[$sub] = true;
            }
        }

        $this->calculateTotals();
    }

    protected function resetPaymentWorkspace(): void
    {
        $this->grandTotal = 0;
        $this->errors_there = [];
        $this->unchecked = [];
        $this->uncheckedAll = [];
        $this->toggleAll = [];
        $this->rowAmounts = [];
        $this->groupItemIds = [];
        $this->loadedGroups = [];
        $this->breakdown = [];
        $this->accordion = [];
        $this->chequeNumbers = [];
        $this->pay_date = null;
        $this->showErrors = false;
        $this->hasPayAll = false;
        $this->hasPayReg = false;
        $this->hasPaySub = false;
        $this->activeType = null;
        $this->pageTitle = null;
    }

    public function render()
    {
        return view('livewire.make-payments.index');
    }
}
