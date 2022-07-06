<?php

namespace Modules\Church\Http\Controllers\Reports;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Church\Models\PaymentDivisionView;
use Modules\Demarcations\Models\Demarcation as ModelsDemarcation;

class NationalAnalysis extends Controller
{
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
            $region['total_amount'] = $this->getRegionalAllTotalContributions($year,$region->id);
            $region['remitting_churches'] = PaymentDivisionView::where('regional_id',$region->id)->where('year',$year)->distinct('church_id')->count();
            
        }
        $selected_years = range(2000, date('Y'));
        return view('church::reports.nationalanalysis',compact('selected_years','year','columns','regions',));
    }


    private function getRegionalTotalContributions($year,$region_id,$trans_type){
        return PaymentDivisionView::where('regional_id',$region_id)->where('year',$year)->where('type',$trans_type)->sum('amount');
    }
   
    private function getRegionalAllTotalContributions($year,$region_id){
        return PaymentDivisionView::where('regional_id',$region_id)->where('year',$year)->sum('amount');
    }
   
}
