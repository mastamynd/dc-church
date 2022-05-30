<?php

namespace Modules\PaymentReception\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentReception extends Model
{
    use HasFactory;
    protected $fillable = ['church_id', 'payment_mode', 'reference', 'phone_number', 'payer_name', 'payer_phone', 'payer_email', 'created_by', 'status', 'posted_by'];

    public function items(){
        return $this->hasMany(PaymentReceptionItems::class, 'payment_reception_id')->orderBy('week', 'asc');
    }
}
