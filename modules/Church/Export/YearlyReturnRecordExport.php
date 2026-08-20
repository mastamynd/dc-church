<?php
namespace Modules\Church\Export;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Modules\Church\Models\PaymentDivisionView;
use Modules\Demarcations\Models\Demarcation as ModelsDemarcation;

class YearlyReturnRecordExport implements FromView
{
    public $year ;
    public function __construct($year)
    {
        $this->year = $year;
    }
    public function view(): View
    {
        $columnsArr = PaymentDivisionView::orderBy('type','asc')->pluck('type')->toArray();
        $columns = array_unique($columnsArr);
        $regions = ModelsDemarcation::where('type','Region')->get();
        $selected_years = range(2000, date('Y'));
        foreach($regions as $region){
            foreach($columns as $column){
                $region[$column] = $this->getRegionalTotalContributions($this->year,$region->id,$column) > 0 ? $this->getRegionalTotalContributions($this->year,$region->id,$column) : '0.0';
            }
            $region['total_amount'] = $this->getRegionalAllTotalContributions($this->year,$region->id) > 0 ? $this->getRegionalAllTotalContributions($this->year,$region->id) : '0.0';
        }

        return view('church::reports.excels.yearlyreturnrecord', [
            'regions' => $regions,'columns'=>$columns
        ]);
    }
    private function getRegionalTotalContributions($year,$region_id,$trans_type){
        return PaymentDivisionView::where('regional_id',$region_id)->where('year',$year)->where('type',$trans_type)->sum('amount');
    }
   
    private function getRegionalAllTotalContributions($year,$region_id){
        return PaymentDivisionView::where('regional_id',$region_id)->where('year',$year)->sum('amount');
    }
}
