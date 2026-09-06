<?php

namespace Modules\Demarcations\Imports;

use App\Abstracts\Import;
use Modules\Demarcations\Models\Bank as Model;

class Banks extends Import
{
    public function model(array $row)
    {
        $bank = Model::withTrashed()
            ->where('company_id', $row['company_id'])
            ->where('code', $row['code'])
            ->first();

        if ($bank) {
            if ($bank->trashed()) {
                $bank->restore();
            }

            $bank->update([
                'name' => $row['name'],
                'enabled' => $row['enabled'] ?? 1,
            ]);

            return null;
        }

        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $row['name'] = trim((string) ($row['name'] ?? ''));
        $row['code'] = trim((string) ($row['code'] ?? ''));
        $row['enabled'] = isset($row['enabled']) ? (int) $row['enabled'] : 1;

        return $row;
    }

    public function rules(): array
    {
        return $this->replaceForBatchRules([
            'name' => 'required|string|max:191',
            'code' => 'required|string|max:50',
            'enabled' => 'nullable|integer|boolean',
        ]);
    }
}
