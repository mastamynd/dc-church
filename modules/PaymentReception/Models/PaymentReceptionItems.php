<?php

namespace Modules\PaymentReception\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentReceptionItems extends Model
{
    use HasFactory;

    protected $fillable = ['payment_reception_id', 'year', 'month', 'week', 'amount', 'topup'];

    public function reception(): BelongsTo
    {
        return $this->belongsTo(PaymentReception::class);
    }
}
