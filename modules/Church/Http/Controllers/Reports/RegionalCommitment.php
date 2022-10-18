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
    public function reportIndex($year = 0)
    {
        $year = $year ==0 ? date('Y') : $year;
        $regions = Demarcation::where('type','Region')->get();
        foreach($regions as $region){
            $region['current_remitting_churches'] = PaymentDivisionView::where('regional_id',$region->id)->where('year',$year)->distinct('church_id')->count();
            $current_church_total = $this->getTotalNoChurches($year,$region->id)->count();
            $region['current_church_total'] = $current_church_total;
            $region['current_commitment_perc']= $this->getCommitmentPerc($region['current_remitting_churches'],$current_church_total);
            $region['prev_remitting_churches'] = PaymentDivisionView::where('regional_id',$region->id)->where('year',$year-1)->distinct('church_id')->count();
            $region['prev_church_total'] = $this->getTotalNoChurches($year-1,$region->id)->count();
            $region['prev_commitment_perc'] = $this->getCommitmentPerc($region['prev_remitting_churches'],$region['prev_church_total']);

        }
        $selected_years = range(2000, date('Y'));$selected_years = range(2000, date('Y'));
        $prev_year = $year-1;
        return view('church::reports.regionalcommitment',compact('selected_years','regions','prev_year','year'));

    }
    private function getCommitmentPerc($remitting_churches,$church_total){
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
        foreach($regions as $region){
            $region['current_remitting_churches'] = PaymentDivisionView::where('regional_id',$region->id)->where('year',$year)->distinct('church_id')->count();
            $current_church_total = $this->getTotalNoChurches($year,$region->id)->count();
            $region['current_church_total'] = $current_church_total;
            $region['current_commitment_perc']= $this->getCommitmentPerc($region['current_remitting_churches'],$current_church_total);
            $region['prev_remitting_churches'] = PaymentDivisionView::where('regional_id',$region->id)->where('year',$year-1)->distinct('church_id')->count();
            $region['prev_church_total'] = $this->getTotalNoChurches($year-1,$region->id)->count();
            $region['prev_commitment_perc'] = $this->getCommitmentPerc($region['prev_remitting_churches'],$region['prev_church_total']);

        }
        $selected_years = range(2000, date('Y'));$selected_years = range(2000, date('Y'));
        $prev_year = $year-1;
        return view('church::reports.print.regionalcommitment',compact('selected_years','regions','prev_year','year'));
    }

}
