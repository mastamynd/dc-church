<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Modules\Church\Models\Church;

class PaymentWorkFlow extends Component
{
    public $configurations;
    public $current_page;
    public $page_data = [];
    public $title;
    public $subtitle;
    public $prev_year;
    public $current_year;
    public $current_workflow;
    public $options;
    public $workflows;
    public $current_workflow_keys;
    public $current_workflow_index;
    public $church_code;
    public $church_details=false;
    public $prev_page;
    public $next_page;

    public function mount()
    {
        $this->current_year = date('Y');
        $this->prev_year = $this->current_year <= 2022 ? 2022 : $this->current_year - 1;
        $this->current_page = 'first-screen-options';
        $this->title = "Make Payment";
        $this->subtitle  = "Select Option";
        $this->workflows = [
            "tithe" => ['church-code'=>'Church Code', 'select-year'=>'Select Year', 'select-month'=>'Select Month', 'select-week'=>'Select Week', 'enter-amount'=>'Enter Amount', 
                'enter-topup'=>'Enter TopUp', 'processing-payment'=>'Processing Payment', 'payment-complete'=>'Payment Completed'],
            "other" => ['church-code'=>'Church Code', 'enter-amount'=>'Enter Amount', 'processing-payment'=>'Processing Payment', 'payment-complete'=>'Payment Completed'],
        ];

        $this->options = [
            "Tithe" => "tithe", "Water Baptism Books" => "other", "Child Dedication Books" => "other", "Marriage Certificates" => "other", "HQ Building" => "other", "Offering" => "other"
        ];
    }

    public function set_option($option){
        $this->title = $option;
        $current_option = $this->options[$option];
        $this->current_workflow = $this->workflows[$current_option];
        $this->current_workflow_keys = array_keys($this->current_workflow);
        $this->current_workflow_index = 0;
        $current_page = $this->current_workflow_keys[$this->current_workflow_index];

        $this->jump_to_page($current_page);

        $page_data['option'] = $option;
    }

    public function jump_to_page($page){
        $this->current_page = $page;
        $pages = $this->current_workflow;

        $pageIndex = array_search($page, $pages);
        $count = count($pages);

        $prevIndex = $pageIndex - 1; 
        $nxtIndex = $pageIndex + 1; 

        $this->prev_page = $prevIndex < 1 ? 'first-screen-options' : $this->current_workflow_keys[$prevIndex];
        $this->next_page = $nxtIndex > $count-1 ? 'payment-complete' : $this->current_workflow_keys[$nxtIndex];

        $this->subtitle = $this->current_workflow[$page];

        if($page == 'first-screen-options'){
            $this->title = "Make Payment";
            $this->subtitle  = "Select Option";
        }

        if($pageIndex > 0){
            for($j = $pageIndex; $j < $count; $j++){
                $pg = $pages[$j];
                $page_data[$pg] = [];
            }
        }
    }

    public function fetch_church_details(){
        $this->church_details = Church::with('senior_pastor', 'subregion')->where('code', $this->church_code)->first();
    }

    public function set_year($yr){
        $page_data['year'] = $yr;
        $this->jump_to_page($this->next_page);
    }

    public function render()
    {
        return view('livewire.payment-work-flow');
    }
}
