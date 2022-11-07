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
    public function reportindex(Request $request ,$year = 0)
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
                $totals[$column] =$totals[$column] + $this->getRegionalTotalContributions($year,$region->id,$column);
            }
            $region['current_total_amount'] = $this->getRegionalAllTotalContributions($year,$region->id);
            isset($totals['current_total_amount']) ? '' : $totals['current_total_amount'] = 0;
            $totals['current_total_amount'] = $totals['current_total_amount'] + $region['current_total_amount'];

            $region['prev_total_amount'] = $this->getRegionalAllTotalContributions(intval($year) - 1,$region->id);
            isset($totals['prev_total_amount']) ? '' : $totals['prev_total_amount'] = 0;
            $totals['prev_total_amount'] = $totals['prev_total_amount'] + $region['prev_total_amount'];

            $region['difference'] = $region['current_total_amount'] -  $region['prev_total_amount'] ;
            isset($totals['difference']) ? '' : $totals['difference'] = 0;
            $totals['difference'] = $totals['difference'] +  $region['difference'] ;

            $region['growth'] = $this->getGrowthPercentage($region['difference'],$region['prev_total_amount']);
            isset($totals['growth']) ? '' : $totals['growth'] = 0;
            $totals['growth'] = $totals['growth'] +  $region['growth'] ;

            $region['remitting_churches'] = PaymentDivisionView::where('regional_id',$region->id)->where('year',$year)->distinct('church_id')->count();
            isset($totals['remitting_churches']) ? '' : $totals['remitting_churches'] = 0;
            $totals['remitting_churches'] = $totals['remitting_churches'] + $region['remitting_churches'];

            isset($totals['churches_count']) ? '' : $totals['churches_count'] = 0;
            $totals['churches_count'] = $totals['churches_count'] + $region['churches_count'];
        }
        $selected_years = range(2000, date('Y'));
        $prev_year = intval($year)-1;
        return view('church::reports.comparisontable',compact('regions','year','prev_year','columns','selected_years','totals'));
    }


    private function getRegionalTotalContributions($year,$region_id,$trans_type){
        return PaymentDivisionView::where('regional_id',$region_id)->where('year',$year)->where('type',$trans_type)->sum('amount');
    }
   
    private function getRegionalAllTotalContributions($year,$region_id){
        return PaymentDivisionView::where('regional_id',$region_id)->where('year',$year)->sum('amount');
    }
    private function getGrowthPercentage($difference,$prev_value){
        if($prev_value > 0 && $difference > 0){
            return $difference/$prev_value * 100;
        }elseif($prev_value > 0 && $difference <= 0){
            return 0;
        }elseif($prev_value == 0 && $difference > 0){
            return 100;
        }else{
            return 0;
        }

        return $prev_value > 0 && $difference > 0 ? $difference/$prev_value * 100 : 0;
    }

    public function printReports($year){
        $columnsArr = PaymentDivisionView::orderBy('type','asc')->pluck('type')->toArray();
        $columns = array_unique($columnsArr);
        $regions = ModelsDemarcation::where('type','Region')->get();
        foreach($regions as $region){
            foreach($columns as $column){
                $region[$column] = $this->getRegionalTotalContributions($year,$region->id,$column);
                isset($totals[$column]) ? '' : $totals[$column] = 0;
                $totals[$column] =$totals[$column] + $this->getRegionalTotalContributions($year,$region->id,$column);
            }
            $region['current_total_amount'] = $this->getRegionalAllTotalContributions($year,$region->id);
            isset($totals['current_total_amount']) ? '' : $totals['current_total_amount'] = 0;
            $totals['current_total_amount'] = $totals['current_total_amount'] + $region['current_total_amount'];

            $region['prev_total_amount'] = $this->getRegionalAllTotalContributions(intval($year) - 1,$region->id);
            isset($totals['prev_total_amount']) ? '' : $totals['prev_total_amount'] = 0;
            $totals['prev_total_amount'] = $totals['prev_total_amount'] + $region['prev_total_amount'];

            $region['difference'] = $region['current_total_amount'] -  $region['prev_total_amount'] ;
            isset($totals['difference']) ? '' : $totals['difference'] = 0;
            $totals['difference'] = $totals['difference'] +  $region['difference'] ;

            $region['growth'] = $this->getGrowthPercentage($region['difference'],$region['prev_total_amount']);
            isset($totals['growth']) ? '' : $totals['growth'] = 0;
            $totals['growth'] = $totals['growth'] +  $region['growth'] ;

            $region['remitting_churches'] = PaymentDivisionView::where('regional_id',$region->id)->where('year',$year)->distinct('church_id')->count();
            isset($totals['remitting_churches']) ? '' : $totals['remitting_churches'] = 0;
            $totals['remitting_churches'] = $totals['remitting_churches'] + $region['remitting_churches'];

            isset($totals['churches_count']) ? '' : $totals['churches_count'] = 0;
            $totals['churches_count'] = $totals['churches_count'] + $region['churches_count'];
        }
        $prev_year = intval($year)-1;
        return view('church::reports.print.ComparisionTable',compact('regions','year','prev_year','columns','totals'));
    }
    
}
