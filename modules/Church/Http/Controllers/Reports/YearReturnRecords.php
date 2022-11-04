<?php

namespace Modules\Church\Http\Controllers\Reports;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Church\Models\PaymentDivisionView;
use Modules\Demarcations\Models\Demarcation as ModelsDemarcation;
use Excel;
use Modules\Church\Export\YearlyReturnRecordExport;

class YearReturnRecords extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function reportIndex(Request $request)
    {
        $columnsArr = PaymentDivisionView::orderBy('type','asc')->pluck('type')->toArray();
        $columns = array_unique($columnsArr);
        $year = isset($request->year) ? $request->year : date('Y');
        $regions = ModelsDemarcation::where('type','Region')->get();
        $selected_years = range(2000, date('Y'));
        foreach($regions as $region){
            foreach($columns as $column){
                $region[$column] = $this->getRegionalTotalContributions($year,$region->id,$column);
            }
            $region['total_amount'] = $this->getRegionalAllTotalContributions($year,$region->id);
        }
        return view('church::reports.yearlyreturns',compact('regions','columns','year','selected_years'));

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
        $selected_years = range(2000, date('Y'));
        foreach($regions as $region){
            foreach($columns as $column){
                $region[$column] = $this->getRegionalTotalContributions($year,$region->id,$column);
            }
            $region['total_amount'] = $this->getRegionalAllTotalContributions($year,$region->id);
        }
        return view('church::reports.print.YearlyReturns',compact('regions','columns','year','selected_years'));
    }
    public function exportExcelReport($year){
        return Excel::download(new YearlyReturnRecordExport($year), 'YearlyReturnRecord'.$year.'.xlsx');
        
    }
}
