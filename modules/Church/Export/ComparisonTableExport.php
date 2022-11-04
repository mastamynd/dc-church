<?php
namespace Modules\Church\Export;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Modules\Church\Models\PaymentDivisionView;
use Modules\Demarcations\Models\Demarcation as ModelsDemarcation;

class ComparisonTableExport implements FromView
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
        foreach($regions as $region){
            foreach($columns as $column){
                $region[$column] = $this->getRegionalTotalContributions($this->year,$region->id,$column);
            }
            $region['current_total_amount'] = $this->getRegionalAllTotalContributions($this->year,$region->id);
            $region['prev_total_amount'] = $this->getRegionalAllTotalContributions(intval($this->year) - 1,$region->id);
            $region['difference'] = $region['current_total_amount'] -  $region['prev_total_amount'] ;
            $region['growth'] = $this->getGrowthPercentage($region['difference'],$region['prev_total_amount']);
            $region['remitting_churches'] = PaymentDivisionView::where('regional_id',$region->id)->where('year',$this->year)->distinct('church_id')->count();
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
    private function getGrowthPercentage($difference,$prev_value){
        return $prev_value > 0 ? $difference/$prev_value * 100 : 100;
    }
}
