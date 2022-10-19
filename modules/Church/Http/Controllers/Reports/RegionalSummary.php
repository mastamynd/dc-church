<?php

namespace Modules\Church\Http\Controllers\Reports;

use App\Abstracts\Http\Controller;
use App\Notifications\Purchase\Payment;
use Demarcation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Matrix\Operators\Division;
use Modules\Church\Models\Church;
use Modules\Church\Models\ChurchHistory;
use Modules\Church\Models\PaymentDivision;
use Modules\Church\Models\PaymentDivisionView;
use Modules\Demarcations\Models\Demarcation as ModelsDemarcation;
use Modules\Demarcations\Models\DemarcationHistory;
use PhpOption\None;

class RegionalSummary extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function unfold_divisions($divisions){
        $divisionArr = [];

        foreach($divisions as $div){
            $divisionArr[$div['code']] = $div['name'];
        }

        return $divisionArr;
    }

    public function paymentDivisionSummaryIndex(Request $request, $year = "current")
    {
        $takeyear = $year == "current" && !isset($request->year) ? date('Y') : $request->year;
        $divisions = setting('church.payment_divisions');

        $divisions = json_decode($divisions, true);

        $division = array_values($divisions);

        $division = $this->unfold_divisions($division);
        // $division = ['dc_secretariat' => 'DC Secretariat', 'contribution_to_hq' => 'Contribution to HQ', 'region' => 'Regional', 'sub_region' => 'Sub Regional', 'pastor_fund' => 'Ukombozi'];

        $selected_years = range(2000, date('Y'));


        $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
        $response = [];
        foreach ($division as $type => $value) {
            if ($type == "pastor_fund") {
                $total = [];
                foreach ($months as $month) {
                    $total[$month] = PaymentDivision::where('year', $takeyear)->sum($month);
                };
                $total['total'] = array_sum($total);
                $total['name'] = "TOTAL";
                $response['total'] = $total;
            }
            $month_record = [];
            foreach ($months as $month) {
                $month_record[$month] = PaymentDivision::where('year', $takeyear)->where('type', $type)->sum($month);
            };
            $month_record['total'] = array_sum($month_record);
            $month_record['name'] = $value;
            $response[$type] = $month_record;
        }


        // return response()->json($response);
        return $this->response('church::reports.national', compact('response', 'selected_years', 'takeyear'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function regionalPaymentDivisionSummary($year)
    {

        $regions = ModelsDemarcation::where('type', 'Region')->get();
        $response = [];
        $divisions = setting('church.payment_divisions');

        $divisions = json_decode($divisions, true);

        $division = array_values($divisions);

        $division = $this->unfold_divisions($division);
        // $division = ['dc_secretariat' => 'DC Secretariat', 'contribution_to_hq' => 'Contribution to HQ', 'region' => 'Regional', 'sub_region' => 'Sub Regional', 'pastor_fund' => 'Ukombozi'];
        $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
        foreach ($regions as $region) {
            $region_report = [];
            foreach ($division as $type => $value) {
                if ($type == "pastor_fund") {
                    $total = [];
                    foreach ($months as $month) {
                        $total[$month] = PaymentDivision::where('year', $year)->where('regional_id', $region->id)->sum($month);
                    };
                    $total['total'] = array_sum($total);
                    $total['name'] = "TOTAL";
                    $region_report['total'] = $total;
                }
                foreach ($months as $month) {
                    $region_report[$type][$month] = PaymentDivision::where('year', $year)->where('regional_id', $region->id)->where('type', $type)->sum($month);
                }
                $region_report[$type]['total'] = array_sum($region_report[$type]);
                $region_report[$type]['name'] = $value;
            }
            $subs = DemarcationHistory::where('parent_id', $region->id)->get();
            $region_report['sub_regions'] = [];
            foreach ($subs as $sub) {
                // return response()->json($sub);
                $sub_report = [];
                foreach ($division as $type => $value) {
                    if ($type == "pastor_fund") {
                        $total_ = [];
                        foreach ($months as $month) {
                            $total_[$month] = PaymentDivision::where('year', $year)->where('sub_regional_id', $sub->demarcation_id)->sum($month);
                            
                        };
                        $total_['total'] = array_sum($total_);
                        $total_['name'] = "TOTAL";
                        $sub_report['total'] = $total;
                        
                    }
                    foreach ($months as $month) {
                        $sub_report[$type][$month] = PaymentDivision::where('type', $type)->where('year', $year)->where('sub_regional_id', $sub->demarcation_id)->sum($month);
                        
                    }
                    $sub_report[$type]['total']= array_sum($sub_report[$type]);
                    $sub_report[$type]['name'] = $value;
                    // return response()->json($sub_report);
                }
                $sub_data = ModelsDemarcation::find($sub->demarcation_id);
                if(isset($sub_data->name)){
                    $region_report['sub_regions'][$sub_data->name] = $sub_report;
                }
            }
            $response[$region->name] = $region_report;
        }
        // return response()->json($response);
        return $this->response('church::reports.regional', compact('response', 'year'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function subRegionPaymentDivisionSummary(Request $request,$year = 0)
    {
        $takeyear = $year == 0 ? date('Y') : $year;
        $churches = PaymentDivisionView::where('year',$takeyear);
        $regional_id =0;
        $sub_regional_id = 0;
        if($request->has('regional_id')){
            $churches = $churches->where('regional_id', $request->regional_id);
            $regional_id =$request->regional_id;
        }
        if($request->has('sub_regional_id')){
            $churches = $churches->where('sub_regional_id', $request->sub_regional_id);
            $sub_regional_id = $request->sub_regional_id;
        }
        $churches = $churches->orderBy('regional_id','asc')->orderBy('amount','Desc')->get();
        $response = [];
        $divisions = setting('church.payment_divisions');

        $divisions = json_decode($divisions, true);

        $division = array_values($divisions);

        $division = $this->unfold_divisions($division);
        // $division = ['dc_secretariat' => 'DC Secretariat', 'contribution_to_hq' => 'Contribution to HQ', 'region' => 'Regional', 'sub_region' => 'Sub Regional', 'pastor_fund' => 'Ukombozi'];
        $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
        foreach($churches as $church){
            isset($response[$church->region_name]) ? '' : $response[$church->region_name] = [];
            $response[$church->region_name]['overseer'] = $church->regional_overseer_name;
            isset($response[$church->region_name]['sub-region'][$church->subregion_name]) ? '' : $response[$church->region_name]['sub-region'][$church->subregion_name] = [];
            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]) ? '' : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type] = [];
            $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['name'] = $division[$church->type];
            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['churches']) ? '' : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['churches'] = [];
            array_push($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['churches'],$church);
            $response[$church->region_name]['sub-region'][$church->subregion_name]['overseer'] = $church->subregional_overseer_name;
        }
        // return response()->json($response);
        return $this->response('church::reports.paymentsummary',compact('response','takeyear','regional_id','sub_regional_id'));
    }

    public function paymentDivisionSummaryIndexPrintReport($year){
        $takeyear = $year;
        $divisions = setting('church.payment_divisions');
        

        $divisions = json_decode($divisions, true);

        $division = array_values($divisions);

        $division = $this->unfold_divisions($division);
        // $division = ['dc_secretariat' => 'DC Secretariat', 'contribution_to_hq' => 'Contribution to HQ', 'region' => 'Regional', 'sub_region' => 'Sub Regional', 'pastor_fund' => 'Ukombozi'];

        $selected_years = range(2000, date('Y'));


        $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
        $response = [];
        foreach ($division as $type => $value) {
            if ($type == "pastor_fund") {
                $total = [];
                foreach ($months as $month) {
                    $total[$month] = PaymentDivision::where('year', $takeyear)->sum($month);
                };
                $total['total'] = array_sum($total);
                $total['name'] = "TOTAL";
                $response['total'] = $total;
            }
            $month_record = [];
            foreach ($months as $month) {
                $month_record[$month] = PaymentDivision::where('year', $takeyear)->where('type', $type)->sum($month);
            };
            $month_record['total'] = array_sum($month_record);
            $month_record['name'] = $value;
            $response[$type] = $month_record;
        }


        // return response()->json($response);
        return $this->response('church::reports.print.national', compact('response', 'selected_years', 'takeyear'));
    }
    public function regionalPaymentDivisionSummaryPrintReport($year){
        $regions = ModelsDemarcation::where('type', 'Region')->get();
        $response = [];
        $divisions = setting('church.payment_divisions');

        $divisions = json_decode($divisions, true);

        $division = array_values($divisions);

        $division = $this->unfold_divisions($division);
        // $division = ['dc_secretariat' => 'DC Secretariat', 'contribution_to_hq' => 'Contribution to HQ', 'region' => 'Regional', 'sub_region' => 'Sub Regional', 'pastor_fund' => 'Ukombozi'];
        $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
        foreach ($regions as $region) {
            $region_report = [];
            foreach ($division as $type => $value) {
                if ($type == "pastor_fund") {
                    $total = [];
                    foreach ($months as $month) {
                        $total[$month] = PaymentDivision::where('year', $year)->where('regional_id', $region->id)->sum($month);
                    };
                    $total['total'] = array_sum($total);
                    $total['name'] = "TOTAL";
                    $region_report['total'] = $total;
                }
                foreach ($months as $month) {
                    $region_report[$type][$month] = PaymentDivision::where('year', $year)->where('regional_id', $region->id)->where('type', $type)->sum($month);
                }
                $region_report[$type]['total'] = array_sum($region_report[$type]);
                $region_report[$type]['name'] = $value;
            }
            $subs = DemarcationHistory::where('parent_id', $region->id)->get();
            $region_report['sub_regions'] = [];
            foreach ($subs as $sub) {
                // return response()->json($sub);
                $sub_report = [];
                foreach ($division as $type => $value) {
                    if ($type == "pastor_fund") {
                        $total_ = [];
                        foreach ($months as $month) {
                            $total_[$month] = PaymentDivision::where('year', $year)->where('sub_regional_id', $sub->demarcation_id)->sum($month);
                            
                        };
                        $total_['total'] = array_sum($total_);
                        $total_['name'] = "TOTAL";
                        $sub_report['total'] = $total;
                        
                    }
                    foreach ($months as $month) {
                        $sub_report[$type][$month] = PaymentDivision::where('type', $type)->where('year', $year)->where('sub_regional_id', $sub->demarcation_id)->sum($month);
                        
                    }
                    $sub_report[$type]['total']= array_sum($sub_report[$type]);
                    $sub_report[$type]['name'] = $value;
                    // return response()->json($sub_report);
                }
                $sub_data = ModelsDemarcation::find($sub->demarcation_id);
                $region_report['sub_regions'][$sub_data->name] = $sub_report;
            }
            $response[$region->name] = $region_report;
        }
        // return response()->json($response);
        return $this->response('church::reports.print.regional', compact('response', 'year'));
    }
    public function subRegionPaymentDivisionSummaryPrintReport($year,$regional_id,$sub_regional_id)
    {
        $takeyear = $year == 0 ? date('Y') : $year;
        $churches = PaymentDivisionView::where('year',$takeyear);
      
        if($regional_id > 0){
            $churches = $churches->where('regional_id', $regional_id);
        }
        if($sub_regional_id > 0){
            $churches = $churches->where('sub_regional_id', $sub_regional_id);
        }
        $churches = $churches->orderBy('regional_id','asc')->orderBy('amount','Desc')->get();
        
        $response = [];
        $divisions = setting('church.payment_divisions');

        $divisions = json_decode($divisions, true);

        $division = array_values($divisions);

        $division = $this->unfold_divisions($division);
        // $division = ['dc_secretariat' => 'DC Secretariat', 'contribution_to_hq' => 'Contribution to HQ', 'region' => 'Regional', 'sub_region' => 'Sub Regional', 'pastor_fund' => 'Ukombozi'];
        $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
        foreach($churches as $church){
            isset($response[$church->region_name]) ? '' : $response[$church->region_name] = [];
            $response[$church->region_name]['overseer'] = $church->regional_overseer_name;
            isset($response[$church->region_name]['sub-region'][$church->subregion_name]) ? '' : $response[$church->region_name]['sub-region'][$church->subregion_name] = [];
            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]) ? '' : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type] = [];
            $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['name'] = $division[$church->type];
            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['churches']) ? '' : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['churches'] = [];
            array_push($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['churches'],$church);
            $response[$church->region_name]['sub-region'][$church->subregion_name]['overseer'] = $church->subregional_overseer_name;
        }
        // return response()->json($response);
        // return $this->response('church::reports.paymentsummary',compact('response','takeyear','regional_id','sub_regional_id'));
        return $this->response('church::reports.print.paymentSummary',compact('response','takeyear','regional_id','year','sub_regional_id'));
    }
}
