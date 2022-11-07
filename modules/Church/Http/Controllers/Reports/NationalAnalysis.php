<?php

namespace Modules\Church\Http\Controllers\Reports;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Church\Models\PaymentDivisionView;
use Modules\Demarcations\Models\Demarcation as ModelsDemarcation;

class NationalAnalysis extends Controller
{
    public function reportindex(Request $request,$year = 0)
    {
        $columnsArr = PaymentDivisionView::orderBy('type','asc')->pluck('type')->toArray();
        $columns = array_unique($columnsArr);
        $year = isset($request->year) ? $request->year : date('Y');
        $regions = ModelsDemarcation::where('type','Region')->get();
        $totals = [];
        foreach($regions as $region){
            foreach($columns as $column){
                $region[$column] = $this->getRegionalTotalContributions($year,$region->id,$column);
                isset($totals[$column]) ? '' : $totals[$column] = 0;
                $totals[$column] = $totals[$column] + $region[$column];
            }
            $region['total_amount'] = $this->getRegionalAllTotalContributions($year,$region->id);
            isset($totals['total_amount']) ? '' : $totals['total_amount'] = 0;
            $totals['total_amount'] = $totals['total_amount'] + $region['total_amount'];

            $region['remitting_churches'] = PaymentDivisionView::where('regional_id',$region->id)->where('year',$year)->distinct('church_id')->count();
            isset($totals['remitting_churches']) ? '' : $totals['remitting_churches'] = 0;
            $totals['remitting_churches'] = $totals['remitting_churches'] + $region['remitting_churches'];

            isset($totals['churches_count'])  ? '' : $totals['churches_count'] = 0;
            $totals['churches_count'] = $totals['churches_count'] = $region['churches_count'];
        }
        $selected_years = range(2000, date('Y'));
        return view('church::reports.nationalanalysis',compact('selected_years','year','columns','regions','totals'));
    }


    private function getRegionalTotalContributions($year,$region_id,$trans_type){
        return PaymentDivisionView::where('regional_id',$region_id)->where('year',$year)->where('type',$trans_type)->sum('amount');
    }
   
    private function getRegionalAllTotalContributions($year,$region_id){
        return PaymentDivisionView::where('regional_id',$region_id)->where('year',$year)->sum('amount');
    }

    public function printReport($year){
        $columnsArr = PaymentDivisionView::orderBy('type','asc')->pluck('type')->toArray();
        $columns = array_unique($columnsArr);
        $regions = ModelsDemarcation::where('type','Region')->get();
        $totals =[];
        foreach($regions as $region){
            foreach($columns as $column){
                $region[$column] = $this->getRegionalTotalContributions($year,$region->id,$column);
                isset($totals[$column]) ? '' : $totals[$column] = 0;
                $totals[$column] = $totals[$column] + $region[$column];
            }
            $region['total_amount'] = $this->getRegionalAllTotalContributions($year,$region->id);
            isset($totals['total_amount']) ? '' : $totals['total_amount'] = 0;
            $totals['total_amount'] = $totals['total_amount'] + $region['total_amount'];

            $region['remitting_churches'] = PaymentDivisionView::where('regional_id',$region->id)->where('year',$year)->distinct('church_id')->count();
            isset($totals['remitting_churches']) ? '' : $totals['remitting_churches'] = 0;
            $totals['remitting_churches'] = $totals['remitting_churches'] + $region['remitting_churches'];

            isset($totals['churches_count'])  ? '' : $totals['churches_count'] = 0;
            $totals['churches_count'] = $totals['churches_count'] = $region['churches_count'];
        }
        
        return view('church::reports.print.nationalAnalysis',compact('year','columns','regions','totals'));
    }
   
}
