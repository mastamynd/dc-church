<?php

namespace Modules\Church\Http\Controllers\Reports;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Church\Models\PaymentDivisionView;
use Modules\Demarcations\Models\Demarcation as ModelsDemarcation;

class ComparisonTables extends Controller
{
    /**
     * Display a listing of the resource.
     *t
     * @return Response
     */
    public function reportindex($year = 0)
    {
        $columnsArr = PaymentDivisionView::orderBy('type','asc')->pluck('type')->toArray();
        $columns = array_unique($columnsArr);
        $year = $year == 0 ? date('Y') : $year;
        $regions = ModelsDemarcation::where('type','Region')->get();
        foreach($regions as $region){
            foreach($columns as $column){
                $region[$column] = $this->getRegionalTotalContributions($year,$region->id,$column);
            }
            $region['current_total_amount'] = $this->getRegionalAllTotalContributions($year,$region->id);
            $region['prev_total_amount'] = $this->getRegionalAllTotalContributions(intval($year) - 1,$region->id);
            $region['difference'] = $region['current_total_amount'] -  $region['prev_total_amount'] ;
            $region['growth'] = $this->getGrowthPercentage($region['difference'],$region['prev_total_amount']);
            $region['remitting_churches'] = PaymentDivisionView::where('regional_id',$region->id)->where('year',$year)->distinct('church_id')->count();
        }
        $selected_years = range(2000, date('Y'));
        $prev_year = intval($year)-1;
        return view('church::reports.comparisontable',compact('regions','year','prev_year','columns','selected_years'));
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
