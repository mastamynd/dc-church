<?php

namespace Modules\PaymentReception\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrationPaymentPayload extends Model
{
    use HasFactory;
    protected $fillable = ['type', 'payload'];
}
