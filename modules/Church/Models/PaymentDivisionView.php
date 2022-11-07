<?php

namespace Modules\Church\Models;

use App\Abstracts\Model;


class PaymentDivisionView extends Model
{
   

    protected $fillable = [];
    protected $table = "view_payment_division_by_type";
    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */

     public function getTotalAmount(){
         return $this->january + $this->february + $this->march + $this->april + $this->may + $this->june + $this->july + $this->august + $this->september + $this->october + $this->november + $this->december;
     }
   
}
