<?php

namespace Modules\Demarcations\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Demarcations\Models\Demarcation;

class DemarcationBanksExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    public function collection()
    {
        $demarcations = Demarcation::with(['bank', 'parent.parent'])
            ->orderByRaw("CASE WHEN type = 'Region' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get();

        $demarcations->each->setAppends([]);

        return $demarcations;
    }

    public function headings(): array
    {
        return [
            'type',
            'name',
            'code',
            'region',
            'bank_name',
            'paybill',
            'account_number',
        ];
    }

    public function map($demarcation): array
    {
        return [
            $demarcation->type,
            $demarcation->name,
            $demarcation->code,
            $this->regionName($demarcation),
            optional($demarcation->bank)->name,
            $demarcation->paybill,
            $demarcation->account_number,
        ];
    }

    protected function regionName($demarcation): string
    {
        if (strtolower((string) $demarcation->type) === 'region') {
            return (string) $demarcation->name;
        }

        if ($demarcation->parent && $demarcation->parent->parent) {
            return (string) $demarcation->parent->parent->name;
        }

        return '';
    }
}
