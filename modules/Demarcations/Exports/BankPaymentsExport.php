<?php

namespace Modules\Demarcations\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Church\Models\PaymentDivision;
use Modules\Demarcations\Models\Demarcation;

class BankPaymentsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    protected string $type;

    protected ?int $regionId;

    protected string $paymentDetails;

    protected string $debitAccount;

    protected string $purposeCode;

    protected int $rowNumber = 0;

    public function __construct(string $type, ?int $regionId = null, ?string $paymentDetails = null)
    {
        $this->type = $type;
        $this->regionId = $regionId;
        $this->paymentDetails = trim((string) $paymentDetails) !== ''
            ? trim((string) $paymentDetails)
            : Carbon::now()->format('F Y') . ' EFT';
        $this->debitAccount = (string) (setting('demarcations.eft_debit_account') ?: '0102092519200');
        $this->purposeCode = (string) (setting('demarcations.eft_purpose_code') ?: '051');
    }

    public function collection()
    {
        $groupColumn = $this->type === 'sub_region' ? 'sub_regional_id' : 'regional_id';

        $totals = PaymentDivision::query()
            ->selectRaw($groupColumn . ' as demarcation_id, SUM(amount + topup) as total')
            ->whereNull('payment_date')
            ->where('type', $this->type)
            ->when($this->regionId, function ($query) {
                $query->where('regional_id', $this->regionId);
            })
            ->groupBy($groupColumn)
            ->havingRaw('SUM(amount + topup) > 0')
            ->pluck('total', 'demarcation_id');

        if ($totals->isEmpty()) {
            return collect();
        }

        $demarcations = Demarcation::query()
            ->leftJoin('banks', function ($join) {
                $join->on('banks.id', '=', 'demarcations.bank_id')
                    ->whereNull('banks.deleted_at');
            })
            ->whereIn('demarcations.id', $totals->keys())
            ->orderBy('demarcations.name')
            ->get([
                'demarcations.id',
                'demarcations.name',
                'demarcations.account_number',
                'demarcations.branch_code',
                'banks.code as bank_code',
            ]);

        return $demarcations->map(function ($demarcation) use ($totals) {
            return (object) [
                'name' => $demarcation->name,
                'account_number' => $demarcation->account_number,
                'bank_code' => $demarcation->bank_code,
                'branch_code' => $demarcation->branch_code,
                'amount' => round((float) $totals->get($demarcation->id, 0), 2),
            ];
        })->values();
    }

    public function headings(): array
    {
        return [
            '',
            'Beneficiary Name',
            'Beneficiary A/c Number',
            'Bank Code',
            'Branch Code',
            '',
            'Payment Amount',
            '',
            'Your Company Debit A/c Number',
            'Purpose of Payment Code',
            'Payment Details',
        ];
    }

    public function map($row): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $row->name,
            $row->account_number,
            $row->bank_code ? str_pad((string) $row->bank_code, 2, '0', STR_PAD_LEFT) : null,
            $row->branch_code,
            'KES',
            $row->amount,
            'ACH',
            $this->debitAccount,
            $this->purposeCode,
            $this->paymentDetails,
        ];
    }
}
