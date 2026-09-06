<?php

namespace Modules\PaymentSchedules\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Church\Models\PaymentDivision;
use Modules\Demarcations\Models\Demarcation;

class PaymentScheduleItem extends Model
{
    protected $table = 'payment_schedule_items';

    protected $fillable = [
        'company_id',
        'payment_schedule_id',
        'demarcation_id',
        'payment_type',
        'amount',
        'bank_id',
        'bank_name',
        'paybill',
        'account_number',
        'status',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_QUEUED = 'queued';
    public const STATUS_SENT = 'sent';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';
    public const STATUS_SKIPPED = 'skipped';

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(PaymentSchedule::class, 'payment_schedule_id');
    }

    public function demarcation(): BelongsTo
    {
        return $this->belongsTo(Demarcation::class, 'demarcation_id');
    }

    public function divisions(): BelongsToMany
    {
        return $this->belongsToMany(
            PaymentDivision::class,
            'payment_schedule_item_division',
            'schedule_item_id',
            'payment_division_id'
        )->withTimestamps();
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(MpesaTransaction::class, 'schedule_item_id');
    }

    public function hasPaybill(): bool
    {
        return filled($this->paybill) && filled($this->account_number);
    }

    public function statusBadge(): string
    {
        return [
            self::STATUS_PENDING => 'secondary',
            self::STATUS_QUEUED => 'info',
            self::STATUS_SENT => 'primary',
            self::STATUS_PAID => 'success',
            self::STATUS_FAILED => 'danger',
            self::STATUS_SKIPPED => 'dark',
        ][$this->status] ?? 'light';
    }
}
