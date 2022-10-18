<?php
namespace Modules\Church\Export;

use Illuminate\Support\Facades\DB;
use Modules\Church\Models\Church;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ChurchesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $db_prefix = DB::getTablePrefix();
        return Church::leftJoin('church_histories as ch', function($join){
            $join->on('ch.church_id', 'churches.id');
            $join->whereNull('ch.deleted_at');
        })->leftJoin('demarcations as d', 'd.id', 'ch.demarcation_id')
        ->leftJoin('demarcation_histories as dh', function($join){
            $join->on('dh.demarcation_id', 'ch.demarcation_id');
            $join->whereNull('dh.deleted_at');
        })
        ->leftJoin('demarcations as dp', 'dp.id', 'dh.parent_id')
        ->select('churches.name', 'churches.code', DB::raw($db_prefix.'d.name as sub_region'), DB::raw($db_prefix.'dp.name as region'))->collect();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Code',
            'Sub Region',
            'Region'
        ];
    }
}
