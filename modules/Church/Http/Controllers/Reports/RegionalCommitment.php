<?php

namespace Modules\Church\Http\Controllers\Reports;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Church\Models\PaymentDivisionView;
use Modules\Demarcations\Models\Demarcation;

class RegionalCommitment extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function reportIndex($year = 0)
    {
        $year = $year ==0 ? date('Y') : $year;
        $regions = Demarcation::where('type','Region')->get();
        foreach($regions as $region){
            $region['remitting_churches'] = PaymentDivisionView::where('regional_id',$region->id)->where('year',$year)->distinct('church_id')->count();
            $church_total = $region->RemittingPerYearchurches($year);
            return response()->json($church_total);
        }

    }
    // private function getCommitmentPerc($year,$regional_id){
        
    //     $total_churches = 
    // }

}
