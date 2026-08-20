<?php


namespace App\Http\Livewire\MakePayments;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Modules\Church\Models\PaymentDivision;

class Index extends Component
{
    public $page1;
    public $page2;
    public $pageTitle;
    public $region;
    public $lists = [];
    public $selectedList = [];
    public $hasPayAll, $hasPaySub, $hasPayReg;
    public $layer2 = ['region', 'sub_region'];
    public $grandTotal = 0;
    public $showErrors = false;
    public $accordion = [];

    public $theTotals = [];

    public $breakdown = [];

    public $toDisplay;

    public $toggleAll = [];
    public $selectedItems = [];
    public $chequeNumbers = [];
    public $pay_date;

    public $errors_there = [];

    public function mount($region)
    {
        $this->region = $region == null || trim($region) == "" ? null : $region;
        $this->getTypes();
    }

    public function showType($d)
    {
        $this->page1 = true;
        $this->pageTitle = ucwords($d);

        $this->hasPayAll = false;
        $this->hasPayReg = false;
        $this->hasPaySub = false;

        if(in_array($d, $this->layer2)){
           
            if($d == 'region'){
                $this->hasPayReg = true;
            }
            else{
                $this->hasPaySub = true;
            }
        }
        else{
            $this->hasPayAll = true;
        }

        $breakdown = PaymentDivision::with(['church', 'pastor.contact', 'subregion', 'region'])->whereNull('payment_date')->where('type', $d)->orderBy('church_id')->orderBy('contact_id');
        if ($this->region) {
            $breakdown = $breakdown->where('regional_id', $this->region);
        }

        $breakdown = $breakdown->get();

        $theData = [];

        foreach($breakdown as $br){
            if(isset($br->region)){
                if(!isset($theData[$br->region->name])){
                    $this->accordion[$br->region->name.'-region'] = false;
                    $theData[$br->region->name] = ["total"=>0, "items"=>[]];
                }

                $sub_regName = $br->subregion->name ?? 'All';

                if(!isset($theData[$br->region->name]['items'][$sub_regName])){
                    $this->accordion[$sub_regName.'-subregion'] = true;
                    $this->selectedList[$br->region->name.$sub_regName] = [];
                    $theData[$br->region->name]["items"][$sub_regName] = ["total"=>0, "items"=>[]];
                    $this->toggleAll[$br->region->name.$sub_regName] = true;
                }

                $this->theTotals[$br->region->name.$sub_regName][$br->id] = round(floatval($br->amount+$br->topup),1);

                $this->selectedList[$br->region->name.$sub_regName][$br->id] = true;
                $theData[$br->region->name]["items"][$sub_regName]["items"][] = $br;
            }
        }

        $this->breakdown = $theData;

        $this->toDisplay = $theData;

        $this->calculateTotals();
    }

    public function calculateTotals(){
        $theData = [];
        $this->grandTotal = 0;
        foreach($this->breakdown as $r=>$rd){
            foreach($rd['items'] as $s=>$sd){
                foreach($sd['items'] as $br){
                    if(!isset($theData[$r])){
                        $theData[$br['region']['name']] = ["total"=>0, "items"=>[]];
                    }

                    if(!isset($theData[$r]['items'][$s])){
                        $theData[$r]['items'][$s] = ["total"=>0, "items"=>[]];
                    }

                    if($this->selectedList[$r.$s][$br['id']]){
                        $theData[$r]['items'][$s]['total']+=round(floatval($br['amount'])+floatval($br['topup']),1);
                        $theData[$r]['total']+=round(floatval($br['amount'])+floatval($br['topup']),1);
                        $this->grandTotal += round(floatval($br['amount'])+floatval($br['topup']),1);
                    }
                    $theData[$r]["items"][$s]["items"][] = $br;
                }    
            }
        }

        $this->grandTotal = round($this->grandTotal, 1);

        $this->breakdown = $theData;
    }

    public function backToLists()
    {
        $this->page1 = null;
        $this->page2 = null;
        $this->grandTotal = 0;
        $this->lists = [];
        $this->errors_there = [];
        $this->selectedList = [];
        $this->getTypes();
        $this->showErrors = false;
    }

    public function getTypes()
    {
        $divisions = setting('church.payment_divisions');
        $divisions = json_decode($divisions, true);

        $paymentsList = PaymentDivision::selectRaw('SUM(amount+topup) as total, type')->whereNull('payment_date');

        if ($this->region) {
            $paymentsList = $paymentsList->where('regional_id', $this->region);
        }

        $paymentsList = $paymentsList->groupBy('type')->get();

        foreach ($divisions as $k => $v) {
            $this->lists[$v['code']] = $v;
        }

        foreach ($paymentsList as $list) {
            $this->lists[$list->type]['amount'] = $list->total;
        }
    }

    public function makePayment()
    {
        if(trim($this->pay_date) == ""){
            $this->errors_there['pay_date'] = true;
            $this->showErrors = true;
            return;
        }
        else{
            $this->errors_there['pay_date'] = false;
            $this->showErrors = false;
        }
        
        $Cheques = [];
        foreach($this->chequeNumbers as $c=>$d){
            if(trim($d) != ""){
                $Cheques[] = $d;
            }
        }

        if(count($Cheques) == 0){
            $this->errors_there['cheques'] = true;
            $this->showErrors = true;
            return;
        }
        else{
            $this->errors_there['cheques'] = false;
            $this->showErrors = false;
        }
        foreach($this->chequeNumbers as $c=>$d){
            $theChequeNumber = $d;
            $getIDs = [];
            if(trim($theChequeNumber) != ""){
                if($c=="All"){
                    foreach($this->selectedList as $rs=>$id){
                        foreach($id as $i=>$d){
                            if($d){
                                $getIDs[] = $i;
                            }
                        }
                    }
                    PaymentDivision::whereIn('id', $getIDs)->update([
                        'payment_date' => Carbon::now(),
                        'bill_id' => 10,
                        'bill_item_id' => 10,
                        'cheque_number' => $theChequeNumber
                    ]);
                }
                else{
                    if($this->hasPayReg){
                        foreach($this->selectedList as $rs=>$id){
                            $rsParts = explode(".", $rs);
                            if(strtolower(trim($rsParts[0])) == strtolower(trim($c))){
                                foreach($id as $i=>$d){
                                    if($d){
                                        $getIDs[] = $i;
                                    }
                                }
                                PaymentDivision::whereIn('id', $getIDs)->update([
                                    'payment_date' => Carbon::now(),
                                    'bill_id' => 10,
                                    'bill_item_id' => 10,
                                    'cheque_number' => $theChequeNumber
                                ]);
                            }
                        }
                    }
                    if($this->hasPaySub){
                        foreach($this->selectedList as $rs=>$id){
                            if(strtolower(trim($rs)) == strtolower(trim($c))){
                                foreach($id as $i=>$d){
                                    if($d){
                                        $getIDs[] = $i;
                                    }
                                }
                                PaymentDivision::whereIn('id', $getIDs)->update([
                                    'payment_date' => Carbon::now(),
                                    'bill_id' => 10,
                                    'bill_item_id' => 10,
                                    'cheque_number' => $theChequeNumber
                                ]);
                            }
                        }
                    }

                }
            }
        }
        $this->backToLists();
    }

    public function toggleAccordion($id){
        $this->accordion[$id] = !$this->accordion[$id];
    }

    public function toggleSelected($sub, $id=false, $action='true'){
        if($id){
            $this->selectedList[$sub][$id] = !$this->selectedList[$sub][$id];
        }
        else{
            $this->toggleAll[$sub] = !($action=='true');
            foreach($this->selectedList[$sub] as $s=>$rs){
                $this->selectedList[$sub][$s] = $this->toggleAll[$sub];
            }
        }
        $this->calculateTotals();
    }

    public function render()
    {
        return view('livewire.make-payments.index');
    }
}
