<?php
namespace Modules\Church\Export;

use Illuminate\Support\Facades\DB;
use Modules\Church\Models\Church;
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
        $db_prefix = DB::getTablePrefix();
        return ChurchView::select('name', 'code', 'sub_region', 'region', 'pastor', 'id_or_passport', 'phone')->collect();
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
