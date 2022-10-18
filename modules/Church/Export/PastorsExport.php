<?php
namespace Modules\Church\Export;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Church\Models\Pastor;

class PastorsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $db_prefix = DB::getTablePrefix();
        return Pastor::join('contacts as c', 'c.id', 'pastors.contact_id')
        ->leftJoin('pastor_histories as ph', function($join){
            $join->on('ph.pastor_id', 'pastors.id');
            $join->whereNull('ph.deleted_at');
        })
				->leftJoin('churches as ch', 'ch.id', 'ph.church_id')
        ->select('c.name', DB::raw($db_prefix.'ch.name as chruch'), 'pastors.id_or_passport', 'pastors.phone', 'pastors.gender')->collect();
    }

    public function headings(): array
    {
        return [
            'Pastor',
            'Church',
            'ID/Passport',
            'Phone',
						'Gender'
        ];
    }
}
