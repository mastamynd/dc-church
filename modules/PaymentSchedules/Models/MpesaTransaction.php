<?php

namespace Modules\PaymentSchedules\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MpesaTransaction extends Model
{
    protected $table = 'mpesa_transactions';

    protected $fillable = [
        'company_id',
        'payment_schedule_id',
        'schedule_item_id',
        'originator_conversation_id',
        'conversation_id',
        'transaction_id',
        'command_id',
        'party_a',
        'party_b',
        'account_reference',
        'amount',
        'result_code',
        'result_desc',
        'request_payload',
        'callback_payload',
        'status',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'callback_payload' => 'array',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(PaymentSchedule::class, 'payment_schedule_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(PaymentScheduleItem::class, 'schedule_item_id');
    }

    public function statusBadge(): string
    {
        return [
            self::STATUS_PENDING => 'warning',
            self::STATUS_ACCEPTED => 'info',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_FAILED => 'danger',
        ][$this->status] ?? 'light';
    }
}
