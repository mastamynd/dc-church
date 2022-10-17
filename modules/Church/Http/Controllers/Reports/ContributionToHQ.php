<?php

namespace Modules\Church\Http\Controllers\Reports;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Church\Models\PaymentDivisionView;
use Modules\Demarcations\Models\Demarcation;

class ContributionToHQ extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function reportIndex(Request $request)
    {
        $year = !isset($request->year) ? $year = date('Y') :$request->year;
        $regional_id = !isset($request->regional_id) ? 'all' : $request->regional_id;
        $response = !isset($request->regional_id) ? Demarcation::where('type','region')->get() : Demarcation::where('id',$request->regional_id)->get();
        $regions =  Demarcation::where('type','region')->get();
        $selected_years = range(2000, date('Y'));
        
        foreach($response as $region){
            $region['churches'] = PaymentDivisionView::where('type','contribution_to_hq')->where('regional_id',$region->id)->get();
        }
        return view('church::reports.contributiontohq',compact('response','year','regional_id','regions','selected_years'));
    }

    public function printReport($year,$regional_id){
        $year = $year;
        $regional_id = $regional_id == 0 ? 'all' : $regional_id;
        $response = $regional_id == 0 ? Demarcation::where('type','region')->get() : Demarcation::where('id',$regional_id)->get();
        $regions =  Demarcation::where('type','region')->get();
        
        
        foreach($response as $region){
            $region['churches'] = PaymentDivisionView::where('type','contribution_to_hq')->where('regional_id',$region->id)->get();
        }
        return view('church::reports.print.ContributionToHq',compact('response','year','regional_id','regions'));
    }
}
