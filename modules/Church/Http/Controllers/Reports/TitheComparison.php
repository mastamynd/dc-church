<?php

namespace Modules\Church\Http\Controllers\Reports;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Church\Models\PaymentDivisionView;

class TitheComparison extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function reportIndex(Request $request ,$fromYear = 0,$toYear=0)
    {
        $fromYear = $fromYear == 0 && !isset($request->fromyear) ? 2014 : $request->fromyear;
        $toYear = $toYear==0 && !isset($request->toyear) ? date('Y') : $request->toyear;
        $response = [];
        foreach(range($fromYear,$toYear) as $year){
            $data['name'] = $year;
            $data['value'] = $this->getYearlyTitheContribution($year);
            $response[] = $data;
        }
        $selected_years = range(2014, date('Y'));
        return view('church::reports.tithecomparison',compact('fromYear','toYear','response','selected_years'));
    }
    private function getYearlyTitheContribution($year){
        return PaymentDivisionView::where('year',$year)->sum('amount');
    }

    public function printReport($fromYear,$toYear){
        $response = [];
        foreach(range($fromYear,$toYear) as $year){
            $data['name'] = $year;
            $data['value'] = $this->getYearlyTitheContribution($year);
            $response[] = $data;
        }
        $selected_years = range(2014, date('Y'));
        return view('church::reports.print.tithecomparison',compact('fromYear','toYear','response','selected_years'));
    }
}
