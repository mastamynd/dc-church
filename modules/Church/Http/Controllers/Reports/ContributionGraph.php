<?php

namespace Modules\Church\Http\Controllers\Reports;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Church\Models\PaymentDivisionView;
use Modules\Demarcations\Models\Demarcation;

class ContributionGraph extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function reportIndex(Request $request,$year = 0)
    {
        $year = $year == 0 && !isset($request->year) ? date('Y') : $request->year;
        $regions = Demarcation::where('type','Region')->get();
        $response = [];
        foreach($regions as $region){
            $data['name']= $region->name;
            $data['current_total'] = $this->getRegionContributionPerYear($year,$region->id);
            $data['prev_total'] = $this->getRegionContributionPerYear($year-1,$region->id);
            $response[] = $data;
        }
        $selected_years = range(2000, date('Y'));
        $prev_year = intval($year)-1;
        return view('church::reports.contributiongraph',compact('selected_years','prev_year','year','response'));

    }
    private function getRegionContributionPerYear($year,$regional_id){
        return PaymentDivisionView::where('regional_id',$regional_id)->where('year',$year)->sum('amount');
    }

    public function printReport($year){
        $year = $year;
        $regions = Demarcation::where('type','Region')->get();
        $response = [];
        foreach($regions as $region){
            $data['name']= $region->name;
            $data['current_total'] = $this->getRegionContributionPerYear($year,$region->id);
            $data['prev_total'] = $this->getRegionContributionPerYear($year-1,$region->id);
            $response[] = $data;
        }
        
        $prev_year = intval($year)-1;
        return view('church::reports.print.ContributionGraph',compact('prev_year','year','response'));
    }
    
}
