<?php

namespace Modules\Church\Models;

use App\Abstracts\Model;
// use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentDivision extends Model
{
    // use HasFactory;

    protected $fillable = ['church_id','regional_id','sub_regional_id','january','february','march','april','may','june','july','august','september','october','november','december','type','amount','year'];
    protected $table = "payment_division";
   
}
