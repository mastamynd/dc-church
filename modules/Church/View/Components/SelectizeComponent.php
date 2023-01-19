<?php

namespace Modules\PaymentVoucher\View\Components;

use Illuminate\View\Component;

class SelectizeComponent extends Component
{
    public $url; 
    public $config; 
    public $multiple;
    public $required;
    public $selected;
    public $label;
    public $noKey;
    public function __construct($label = "Select", $config)
    {
        $this->url = isset($config['url']) ? $config['url'] : false;
        $this->config = $config;
        $this->label = $label;
        $this->noKey = isset($config['noKey']);
        $this->multiple = isset($config['multiple']) && $config['multiple'] ? 'multiple' : '';
        $this->required = isset($config['required']) ? 'required' : '';
        $this->selected = isset($config['selected']) ? $config['selected'] : '';

        if($this->multiple!='' && gettype($this->selected) != gettype([])){
            $this->selected = [$this->selected];
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('church::components.selectize-component');
    }
}
