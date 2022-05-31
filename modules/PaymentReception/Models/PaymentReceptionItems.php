<?php

namespace Modules\PaymentReception\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentReceptionItems extends Model
{
    use HasFactory;

    protected $fillable = ['payment_reception_id', 'year', 'month', 'week', 'amount', 'topup'];
}
