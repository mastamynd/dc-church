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
        $region_id = isset($request->region_id) && $request->region_id!= 'all' ? $request->region_id : 0;
        $sub_region_id = isset($request->sub_region_id) && $request->sub_region_id != 'all' && $request->sub_region_id != '' ? $request->sub_region_id : 0;
        $divisions = json_decode($divisions, true);

        $division = array_values($divisions);

        $division = $this->unfold_divisions($division);
        // $division = ['dc_secretariat' => 'DC Secretariat', 'contribution_to_hq' => 'Contribution to HQ', 'region' => 'Regional', 'sub_region' => 'Sub Regional', 'pastor_fund' => 'Ukombozi'];

        $selected_years = range(2000, date('Y'));
        $regions = ModelsDemarcation::where('type', 'Region')->orderBy('name','asc')->get();
        

        // return response()->json(($region_id));

        $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
        $response = [];
        $r_totals = [];
        $loop = 1;
        foreach ($division as $type => $value) {
            if ( in_array($type,['pastor_fund','pastors_fund'])) {
                $total = [];
                foreach ($months as $month) {
                    $count = PaymentDivision::query();
                    $count = $count->where('year', $takeyear);
                    $count = $region_id > 0 ? $count->where('regional_id',$region_id) : $count;
                    $count = $sub_region_id > 0 ? $count->where('sub_regional_id',$sub_region_id) : $count;
                    $total[$month] = $count->sum($month) ;
                    
                };
                $total['total'] = array_sum($total);
                $total['name'] = "TOTAL";
                $response['total'] = $total;
                // $r_totals[$][] = $count->sum($total);
            }
            $month_record = [];
            foreach ($months as $month) {
                $count_m = PaymentDivision::query();
                $count_m = $count_m->where('year', $takeyear)->where('type', $type);
                $count_m = $region_id > 0 ? $count_m->where('regional_id',$region_id) : $count_m;
                $count_m = $sub_region_id > 0 ? $count_m->where('sub_regional_id',$sub_region_id) : $count_m;

                $month_record[$month] = $count_m->sum($month);
                
            };
            $month_record['total'] = array_sum($month_record);
            $month_record['name'] = $value;
            $response[$type] = $month_record;
        }

        $s_total = $response['total'];
        unset($response['total']);
        // return response()->json($response);

        return $this->response('church::reports.national', compact('response', 'selected_years', 'takeyear','regions','region_id','sub_region_id','s_total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function regionalPaymentDivisionSummary(Request $request,$year,$region_id=0)
    {

        $reqion_id = isset($request->region_id) ? $request->region_id : $region_id;
        
        $year = isset($request->year) ? $request->year : $year;
        $regions = $reqion_id == 0 ? ModelsDemarcation::where('type', 'Region')->get() :  ModelsDemarcation::where('type', 'Region')->where('id',$reqion_id)->get();
        $all_regions = ModelsDemarcation::where('type', 'Region')->orderBy('name','asc')->get();
        $selected_years = range(2000, date('Y'));
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
                
                foreach ($months as $month) {
                    $region_report[$type][$month] = PaymentDivision::where('year', $year)->where('regional_id', $region->id)->where('type', $type)->sum($month);
                }
                $region_report[$type]['total'] = array_sum($region_report[$type]);
                $region_report[$type]['name'] = $value;
               
            }
            $total = [];
            foreach ($months as $month) {
                $total[$month] = PaymentDivision::where('year', $year)->where('regional_id', $region->id)->sum($month);
            };
            $total['total'] = array_sum($total);
            $total['name'] = "TOTAL";
            $region_report['total'] = $total;

            $subs = DemarcationHistory::where('parent_id', $region->id)->get();
            $region_report['sub_regions'] = [];
            foreach ($subs as $sub) {
                // return response()->json($sub);
                $sub_report = [];
                foreach ($division as $type => $value) {
                   
                    foreach ($months as $month) {
                        $sub_report[$type][$month] = PaymentDivision::where('type', $type)->where('year', $year)->where('sub_regional_id', $sub->demarcation_id)->sum($month);
                        
                    }
                    $sub_report[$type]['total']= array_sum($sub_report[$type]);
                    $sub_report[$type]['name'] = $value;
                    
                }
                $total_ = [];
                foreach ($months as $month) {
                    $total_[$month] = PaymentDivision::where('year', $year)->where('sub_regional_id', $sub->demarcation_id)->sum($month);
                    
                };
                $total_['total'] = array_sum($total_);
                $total_['name'] = "TOTAL";
                $sub_report['total'] = $total_;

                // return response()->json($sub_report);
                $sub_data = ModelsDemarcation::find($sub->demarcation_id);
                if(isset($sub_data->name)){
                    $region_report['sub_regions'][$sub_data->name] = $sub_report;
                }
            }
            $response[$region->name] = $region_report;
        }
        // return response()->json($response);
        return $this->response('church::reports.regional', compact('response', 'year','all_regions','selected_years','reqion_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function subRegionPaymentDivisionSummary(Request $request,$year = 0,$region_id = 0,$sub_region_id)
    {
        $takeyear = $year == 0 ? date('Y') : $year;
        $regional_id = isset($request->regional_id) ? $request->regional_id : $region_id;
        $sub_regional_id = isset($request->sub_regional_id) ? $request->sub_regional_id : $sub_region_id;
        $churches = PaymentDivisionView::where('year',$takeyear);
        $regional_id = isset($request->regional_id) ? $request->regional_id : $region_id;
        $sub_regional_id = isset($request->sub_regional_id) ? $request->sub_regional_id : $sub_region_id;
        $churches = $regional_id > 0 ? $churches->where('regional_id', $regional_id) : $churches; 
        $churches = $sub_regional_id > 0 ? $churches->where('sub_regional_id', $sub_regional_id) : $churches;
        $churches = $churches->orderBy('regional_id','asc')->orderBy('amount','Desc')->get();
        $remiting = $churches->pluck('id')->toArray();

        $churches_none = Church::whereNotIn('id',$remiting)->join('church_histories as xch','xch.church_id','churches.id')->whereNull('xch.deleted_at')->join('demarcation_histories as xdh','xdh.demarcation_id','xch.demarcation_id')->whereNull('xch.deleted_at')->join('demarcations as subr','subr.id','xch.demarcation_id')->join('demarcations as reg','reg.id','xdh.parent_id')->leftJoin('demarcation_overseers as dem_oversee','dem_oversee.demarcation_id','reg.id')->leftJoin('pastors as pst','pst.id','dem_oversee.pastor_id')->leftJoin('contacts as con','con.id','pst.contact_id')->leftJoin('demarcation_overseers as sub_over','sub_over.demarcation_id','subr.id')->leftJoin('pastors as subpst','subpst.id','sub_over.pastor_id')->leftJoin('contacts as subcon','subcon.id','subpst.contact_id')->selectRaw('1dt_churches.*,1dt_xch.demarcation_id as sub_regional_id,1dt_xdh.parent_id as regional_id,1dt_subr.name as subregion_name,1dt_reg.name as region_name,1dt_con.name as regional_overseer_name,1dt_subcon.name as subregional_overseer_name');
        $churches_none = $regional_id > 0 ? $churches_none->where('regional_id', $regional_id) : $churches_none; 
        $churches_none =  $sub_regional_id > 0 ? $churches_none->where('sub_regional_id', $sub_regional_id) : $churches_none;
        $churches_none = $churches_none->orderBy('regional_id','asc')->get();

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

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['january']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['january']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['january'] + $church->january : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['january'] = $church->january;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['february']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['february']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['february'] + $church->february : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['february'] = $church->february;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['march']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['march']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['march'] + $church->march : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['march'] = $church->march;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['april']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['april']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['april'] + $church->april : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['april'] = $church->april;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['may']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['may']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['may'] + $church->may : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['may'] = $church->may;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['june']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['june']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['june'] + $church->june : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['june'] = $church->june;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['july']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['july']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['july'] + $church->july : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['july'] = $church->july;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['august']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['august']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['august'] + $church->august : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['august'] = $church->august;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['september']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['september']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['september'] + $church->september : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['september'] = $church->september;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['october']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['october']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['october'] + $church->october : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['october'] = $church->october;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['november']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['november']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['november'] + $church->november : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['november'] = $church->november;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['december']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['december']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['december'] + $church->december : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['december'] = $church->december;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['totals']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['totals']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['totals'] + $church->getTotalAmount() : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['totals'] = $church->getTotalAmount();


            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['churches']) ? '' : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['churches'] = [];
            array_push($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['churches'],$church);
            $response[$church->region_name]['sub-region'][$church->subregion_name]['overseer'] = $church->subregional_overseer_name;
            
            
        }
        foreach($churches_none as $church){
            foreach($division as $key=>$type){
                isset($response[$church->region_name]) ? '' : $response[$church->region_name] = [];
                $response[$church->region_name]['overseer'] = $church->regional_overseer_name;
                isset($response[$church->region_name]['sub-region'][$church->subregion_name]) ? '' : $response[$church->region_name]['sub-region'][$church->subregion_name] = [];
                isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$key]) ? '' : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$key] = [];
                $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$key]['name'] = $type;
                foreach($months as $month){

                    isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$key]['total'][$month]) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$key]['total'][$month]= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$key]['total'][$month] + 0 : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$key]['total'][$month] = 0;
                }
    
    
                isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$key]['total']['totals']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$key]['total']['totals']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$key]['total']['totals'] + 0 : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$key]['total']['totals'] = 0;
    
    
                isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$key]['churches']) ? '' : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$key]['churches'] = [];
                array_push($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$key]['churches'],$church);
                $response[$church->region_name]['sub-region'][$church->subregion_name]['overseer'] = $church->subregional_overseer_name;
            }
        }
        // return response()->json($response);
        return $this->response('church::reports.paymentsummary',compact('response','takeyear','regional_id','sub_regional_id'));
    }

    public function paymentDivisionSummaryIndexPrintReport($year,$region_id =0,$sub_region_id = 0){
        $takeyear = $year;
        $divisions = setting('church.payment_divisions');
        

        $divisions = json_decode($divisions, true);

        $division = array_values($divisions);

        $division = $this->unfold_divisions($division);
        // $division = ['dc_secretariat' => 'DC Secretariat', 'contribution_to_hq' => 'Contribution to HQ', 'region' => 'Regional', 'sub_region' => 'Sub Regional', 'pastor_fund' => 'Ukombozi'];

        $selected_years = range(2000, date('Y'));
        $regional_id = $region_id > 0 ? $region_id : 0;
        $sub_regional_id = $sub_region_id > 0 ? $sub_region_id: 0;


        $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
        $response = [];
        foreach ($division as $type => $value) {
            if (in_array($type,['pastor_fund','pastors_fund'])) {
                $total = [];
                foreach ($months as $month) {
                    $count = PaymentDivision::query();
                    $count = $count->where('year', $takeyear);
                    $count = $regional_id > 0 ? $count->where('regional_id',$regional_id) : $count;
                    $count = $sub_regional_id > 0 ? $count->where('sub_regional_id',$sub_regional_id) : $count;
                    $total[$month] = $count->sum($month) ;
                    
                };
                $total['total'] = array_sum($total);
                $total['name'] = "TOTAL";
                $response['total'] = $total;
            }
            $month_record = [];
            foreach ($months as $month) {
                $count_m = PaymentDivision::query();
                $count_m = $count_m->where('year', $takeyear)->where('type', $type);
                $count_m = $regional_id > 0 ? $count_m->where('regional_id',$regional_id) : $count_m;
                $count_m = $sub_regional_id > 0 ? $count_m->where('sub_regional_id',$sub_regional_id) : $count_m;
                $month_record[$month] = $count_m->sum($month);
            };
            $month_record['total'] = array_sum($month_record);
            $month_record['name'] = $value;
            $response[$type] = $month_record;
        }

        $s_total = $response['total'];
        unset($response['total']);

        // return response()->json($response);
        return $this->response('church::reports.print.national', compact('response', 'selected_years', 'takeyear','s_total'));
    }
    public function regionalPaymentDivisionSummaryPrintReport($year,$region_id = 0){
        $regions = $region_id == 0 ? ModelsDemarcation::where('type', 'Region')->get() : ModelsDemarcation::where('type', 'Region')->where('id',$region_id)->get();
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
               
                foreach ($months as $month) {
                    $region_report[$type][$month] = PaymentDivision::where('year', $year)->where('regional_id', $region->id)->where('type', $type)->sum($month);
                }
                $region_report[$type]['total'] = array_sum($region_report[$type]);
                $region_report[$type]['name'] = $value;
               
            }
            $total = [];
            foreach ($months as $month) {
                $total[$month] = PaymentDivision::where('year', $year)->where('regional_id', $region->id)->sum($month);
            };
            $total['total'] = array_sum($total);
            $total['name'] = "TOTAL";
            $region_report['total'] = $total;

            $subs = DemarcationHistory::where('parent_id', $region->id)->get();
            $region_report['sub_regions'] = [];
            foreach ($subs as $sub) {
                // return response()->json($sub);
                $sub_report = [];
                foreach ($division as $type => $value) {
                   
                    foreach ($months as $month) {
                        $sub_report[$type][$month] = PaymentDivision::where('type', $type)->where('year', $year)->where('sub_regional_id', $sub->demarcation_id)->sum($month);
                        
                    }
                    $sub_report[$type]['total']= array_sum($sub_report[$type]);
                    $sub_report[$type]['name'] = $value;
                    if (in_array($type,['pastor_fund','pastors_fund'])) {
                        
                        
                    }
                    // return response()->json($sub_report);
                }
                $total_ = [];
                foreach ($months as $month) {
                    $total_[$month] = PaymentDivision::where('year', $year)->where('sub_regional_id', $sub->demarcation_id)->sum($month);
                    
                };
                $total_['total'] = array_sum($total_);
                $total_['name'] = "TOTAL";
                $sub_report['total'] = $total_;
                
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

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['january']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['january']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['january'] + $church->january : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['january'] = $church->january;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['february']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['february']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['february'] + $church->february : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['february'] = $church->february;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['march']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['march']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['march'] + $church->march : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['march'] = $church->march;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['april']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['april']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['april'] + $church->april : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['april'] = $church->april;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['may']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['may']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['may'] + $church->may : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['may'] = $church->may;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['june']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['june']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['june'] + $church->june : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['june'] = $church->june;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['july']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['july']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['july'] + $church->july : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['july'] = $church->july;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['august']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['august']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['august'] + $church->august : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['august'] = $church->august;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['september']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['september']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['september'] + $church->september : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['september'] = $church->september;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['october']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['october']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['october'] + $church->october : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['october'] = $church->october;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['november']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['november']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['november'] + $church->november : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['november'] = $church->november;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['december']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['december']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['december'] + $church->december : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['december'] = $church->december;

            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['totals']) ? $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['totals']= $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['totals'] + $church->getTotalAmount() : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['total']['totals'] = $church->getTotalAmount();

            

            $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['name'] = $division[$church->type];
            isset($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['churches']) ? '' : $response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['churches'] = [];
            array_push($response[$church->region_name]['sub-region'][$church->subregion_name]['types'][$church->type]['churches'],$church);
            $response[$church->region_name]['sub-region'][$church->subregion_name]['overseer'] = $church->subregional_overseer_name;
        }
        // return response()->json($response);
        // return $this->response('church::reports.paymentsummary',compact('response','takeyear','regional_id','sub_regional_id'));
        return $this->response('church::reports.print.paymentSummary',compact('response','takeyear','regional_id','year','sub_regional_id'));
    }
    public function getSubregionsByRegionID(Request $request){
        $subs = DemarcationHistory::where('parent_id', $request->region_id)->with('demarcation')->get()->sortBy('demarcation.name',SORT_REGULAR,false)->values();
        return response()->json($subs);
    }
}
