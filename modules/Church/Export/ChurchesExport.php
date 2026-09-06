<?php
namespace Modules\Church\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Church\Models\ChurchView;

class ChurchesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ChurchView::orderBy('churches.name')->get()->map(function ($row) {
            return [
                $row->name,
                $row->code,
                $row->sub_region,
                $row->region,
                $row->pastor,
                $row->id_or_passport,
                $row->phone,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Name',
            'Code',
            'Sub Region',
            'Region',
            'Pastor',
            'ID/Passport',
            'Phone'
        ];
    }
}
