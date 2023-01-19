<?php

namespace Modules\Church\Http\Controllers\Reports;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Church\Models\Church;
use Modules\Church\Models\ChurchHistory;
use Modules\Church\Models\PaymentDivisionView;
use Modules\Demarcations\Models\Demarcation;
use Modules\Demarcations\Models\DemarcationHistory;

class RegionalCommitment extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function reportIndex(Request $request,$year = 0)
    {
        $year = $year ==0 && !isset($request->year) ? date('Y') : $request->year;
        $regions = Demarcation::where('type','Region')->get();
        $totals =[];
        foreach($regions as $region){
            $region['current_remitting_churches'] = PaymentDivisionView::where('regional_id',$region->id)->where('year',$year)->distinct('church_id')->count();
            isset($totals['current_remitting_churches']) ? '' : $totals['current_remitting_churches'] = 0;
            $totals['current_remitting_churches'] = $totals['current_remitting_churches'] + $region['current_remitting_churches'];

            $current_church_total = $this->getTotalNoChurches($year,$region->id)->count();
            $region['current_church_total'] = $current_church_total;
            isset($totals['current_church_total']) ? '' : $totals['current_church_total'] = 0;
            $totals['current_church_total']  = $totals['current_church_total']  + $region['current_church_total'];

            $region['current_commitment_perc']= $this->getCommitmentPerc($region['current_remitting_churches'],$current_church_total);
            isset($totals['current_commitment_perc']) ? '' : $totals['current_commitment_perc'] = 0;
            $totals['current_commitment_perc'] = $totals['current_commitment_perc'] + $region['current_commitment_perc'];

            $region['prev_remitting_churches'] = PaymentDivisionView::where('regional_id',$region->id)->where('year',$year-1)->distinct('church_id')->count();
            isset($totals['prev_remitting_churches']) ? '' : $totals['prev_remitting_churches'] = 0;
            $totals['prev_remitting_churches'] = $totals['prev_remitting_churches'] +$region['prev_remitting_churches'];

            $region['prev_church_total'] = $this->getTotalNoChurches($year-1,$region->id)->count();
            isset($totals['prev_church_total']) ? '' : $totals['prev_church_total'] = 0;
            $totals['prev_church_total'] = $totals['prev_church_total'] + $region['prev_church_total'];

            $region['prev_commitment_perc'] = $this->getCommitmentPerc($region['prev_remitting_churches'],$region['prev_church_total']);
            isset($totals['prev_commitment_perc']) ? '' : $totals['prev_commitment_perc'] = 0;
            $totals['prev_commitment_perc'] = $totals['prev_commitment_perc'] + $region['prev_commitment_perc'];

            isset($totals['churches_count']) ? '' : $totals['churches_count'] =0;
            $totals['churches_count'] = $totals['churches_count'] + $region['churches_count'];

        }
        $selected_years = range(2000, date('Y'));$selected_years = range(2000, date('Y'));
        $prev_year = $year-1;
        return view('church::reports.regionalcommitment',compact('selected_years','regions','prev_year','year','totals'));

    }
    private function getCommitmentPerc($remitting_churches,$church_total){
        if($church_total == 0 ){
            return 0;
        }elseif($remitting_churches > 0 && $church_total > 0){
            return $remitting_churches/$church_total * 100;
        }else{
            return 0;
        }

        return $church_total == 0 ? 100 : $remitting_churches/$church_total * 100;
    }
    private function getTotalNoChurches($year,$regional_id){
		$subregion_ids = DemarcationHistory::where('parent_id', $regional_id)->select('demarcation_id')->get()->pluck('demarcation_id'); 
		$churches = ChurchHistory::withTrashed()->whereIn('demarcation_id', $subregion_ids)->where(function($where) use($year){
            $where->whereRaw('YEAR(deleted_at) >='.$year);
            $where->orWhereRaw('deleted_at is null');
        })->whereYear('created_at','<=',$year)->get();
		
		return $churches;
	}

    public function PrintReport($year){
        $year = $year;
        $regions = Demarcation::where('type','Region')->get();
        $totals =[];
        foreach($regions as $region){
            $region['current_remitting_churches'] = PaymentDivisionView::where('regional_id',$region->id)->where('year',$year)->distinct('church_id')->count();
            isset($totals['current_remitting_churches']) ? '' : $totals['current_remitting_churches'] = 0;
            $totals['current_remitting_churches'] = $totals['current_remitting_churches'] + $region['current_remitting_churches'];

            $current_church_total = $this->getTotalNoChurches($year,$region->id)->count();
            $region['current_church_total'] = $current_church_total;
            isset($totals['current_church_total']) ? '' : $totals['current_church_total'] = 0;
            $totals['current_church_total']  = $totals['current_church_total']  + $region['current_church_total'];

            $region['current_commitment_perc']= $this->getCommitmentPerc($region['current_remitting_churches'],$current_church_total);
            isset($totals['current_commitment_perc']) ? '' : $totals['current_commitment_perc'] = 0;
            $totals['current_commitment_perc'] = $totals['current_commitment_perc'] + $region['current_commitment_perc'];

            $region['prev_remitting_churches'] = PaymentDivisionView::where('regional_id',$region->id)->where('year',$year-1)->distinct('church_id')->count();
            isset($totals['prev_remitting_churches']) ? '' : $totals['prev_remitting_churches'] = 0;
            $totals['prev_remitting_churches'] = $totals['prev_remitting_churches'] +$region['prev_remitting_churches'];

            $region['prev_church_total'] = $this->getTotalNoChurches($year-1,$region->id)->count();
            isset($totals['prev_church_total']) ? '' : $totals['prev_church_total'] = 0;
            $totals['prev_church_total'] = $totals['prev_church_total'] + $region['prev_church_total'];

            $region['prev_commitment_perc'] = $this->getCommitmentPerc($region['prev_remitting_churches'],$region['prev_church_total']);
            isset($totals['prev_commitment_perc']) ? '' : $totals['prev_commitment_perc'] = 0;
            $totals['prev_commitment_perc'] = $totals['prev_commitment_perc'] + $region['prev_commitment_perc'];

            isset($totals['churches_count']) ? '' : $totals['churches_count'] =0;
            $totals['churches_count'] = $totals['churches_count'] + $region['churches_count'];

        }
        $selected_years = range(2000, date('Y'));$selected_years = range(2000, date('Y'));
        $prev_year = $year-1;
        return view('church::reports.print.regionalcommitment',compact('selected_years','regions','prev_year','year','totals'));
    }

}
