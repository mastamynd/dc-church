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
        $pastors = Pastor::leftJoin('contacts as c', 'c.id', 'pastors.contact_id')
        ->leftJoin('pastor_histories as ph', function($join){
            $join->on('ph.pastor_id','pastors.id');
            $join->whereNull('ph.deleted_at');
        })->leftJoin('churches as ch', function($join){
            $join->on('ch.id', 'ph.church_id');
            $join->whereNull('ch.deleted_at');
        })->leftJoin('church_histories as chh', function($join){
            $join->on('ch.id', 'chh.church_id');
            $join->whereNull('chh.deleted_at');
        })->leftJoin('demarcation_histories as dh', function($join){
            $join->on('chh.demarcation_id', 'dh.demarcation_id');
            $join->whereNull('dh.deleted_at');
        })->leftJoin('demarcations as dS', function($join){
            $join->on('dS.id', 'chh.demarcation_id');
        })->leftJoin('demarcations as dR', function($join){
            $join->on('dR.id', 'dh.parent_id');
        });

        return $pastors->select('c.name', DB::raw($db_prefix.'ch.name as church'), 'pastors.phone', 'pastors.id_or_passport', 'pastors.ukumbozi_number', 
            'pastors.gender', DB::raw($db_prefix.'dR.name as region'), DB::raw($db_prefix.'dS.name as sub_region'))
            ->orderBy('pastors.id', 'desc')->collect();
    }

    public function headings(): array
    {
        return [
            'Pastor',
            'Church',
            'Phone',
            'ID/Passport',
            'Ukombozi',
		    'Gender',
            'Region',
            'Sub Region'
        ];
    }
}
