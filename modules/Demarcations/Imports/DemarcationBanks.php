<?php

namespace Modules\Demarcations\Imports;

use App\Abstracts\Import;
use Modules\Demarcations\Models\Bank;
use Modules\Demarcations\Models\Demarcation;

class DemarcationBanks extends Import
{
    public $type;

    public function __construct($type = 'Region')
    {
        parent::__construct();

        $this->type = $type;
    }

    public function model(array $row)
    {
        $demarcation = Demarcation::find($row['demarcation_id'] ?? 0);

        if (!$demarcation) {
            return null;
        }

        $hasBankingDetails = !empty($row['bank_id']) || $row['paybill'] !== '' || $row['account_number'] !== '';

        if (!$hasBankingDetails) {
            return null;
        }

        $data = [
            'paybill' => $row['paybill'] !== '' ? $row['paybill'] : null,
            'account_number' => $row['account_number'] !== '' ? $row['account_number'] : null,
        ];

        if (!empty($row['bank_id'])) {
            $data['bank_id'] = $row['bank_id'];
        }

        $demarcation->update($data);

        return null;
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $code = trim((string) ($row['code'] ?? $row['region_code'] ?? $row['sub_region_code'] ?? $row['subregion_code'] ?? ''));
        $bankName = trim((string) ($row['bank_name'] ?? $row['bank'] ?? $row['bank_code'] ?? ''));
        $rowType = trim((string) ($row['type'] ?? ''));

        $query = Demarcation::query()->where('code', $code);

        if ($rowType !== '') {
            $query->where('type', $rowType);
        }

        $demarcation = $code === '' ? null : $query->first();
        $bank = $this->findBank($bankName);

        $row['code'] = $code;
        $row['bank_name'] = $bankName;
        $row['demarcation_id'] = $demarcation->id ?? 0;
        $row['bank_id'] = $bank->id ?? ($bankName === '' ? null : 0);
        $row['paybill'] = trim((string) ($row['paybill'] ?? $row['paybill_number'] ?? ''));
        $row['account_number'] = trim((string) ($row['account_number'] ?? $row['account'] ?? ''));

        return $row;
    }

    public function rules(): array
    {
        return $this->replaceForBatchRules([
            'code' => 'required|string',
            'demarcation_id' => 'required|integer|not_in:0',
            'bank_name' => 'nullable|string',
            'bank_id' => 'nullable|integer|not_in:0',
            'paybill' => 'nullable|string|max:50',
            'account_number' => 'nullable|string|max:50',
        ]);
    }

    public function customValidationMessages()
    {
        return [
            'demarcation_id.not_in' => trans('demarcations::general.import_unknown_code'),
            'bank_id.not_in' => trans('demarcations::general.import_unknown_bank'),
        ];
    }

    protected function findBank($value)
    {
        if ($value === '') {
            return null;
        }

        return Bank::where(function ($query) use ($value) {
            $query->where('name', $value)
                ->orWhere('code', $value)
                ->orWhereRaw('LOWER(name) = ?', [mb_strtolower($value)])
                ->orWhereRaw('LOWER(code) = ?', [mb_strtolower($value)]);
        })->first();
    }
}
