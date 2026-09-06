<?php

namespace Modules\PaymentSchedules\Models;

use App\Abstracts\Model;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentSchedule extends Model
{
    protected $table = 'payment_schedules';

    protected $fillable = [
        'company_id',
        'billing_cycle_id',
        'type',
        'scheduled_date',
        'status',
        'total_amount',
        'submitted_at',
        'triggered_by',
        'triggered_at',
        'created_from',
        'created_by',
    ];

    protected $dates = ['scheduled_date', 'submitted_at', 'triggered_at', 'deleted_at'];

    public $sortable = ['scheduled_date', 'type', 'status', 'total_amount', 'created_at'];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING_APPROVAL = 'pending_approval';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_OTP_SENT = 'otp_sent';
    public const STATUS_QUEUED = 'queued';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_PAID = 'paid';
    public const STATUS_PARTIALLY_PAID = 'partially_paid';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    public const ACTIVE_STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_PENDING_APPROVAL,
        self::STATUS_APPROVED,
        self::STATUS_OTP_SENT,
        self::STATUS_QUEUED,
        self::STATUS_PROCESSING,
        self::STATUS_FAILED,
        self::STATUS_PARTIALLY_PAID,
    ];

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(BillingCycle::class, 'billing_cycle_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PaymentScheduleItem::class, 'payment_schedule_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(ScheduleApproval::class, 'payment_schedule_id')->orderBy('level');
    }

    public function otps(): HasMany
    {
        return $this->hasMany(ScheduleOtp::class, 'payment_schedule_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(MpesaTransaction::class, 'payment_schedule_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function triggerer()
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, [
            self::STATUS_DRAFT,
            self::STATUS_PENDING_APPROVAL,
            self::STATUS_APPROVED,
            self::STATUS_OTP_SENT,
            self::STATUS_QUEUED,
            self::STATUS_FAILED,
        ], true);
    }

    public function currentApproval(): ?ScheduleApproval
    {
        return $this->approvals->firstWhere('status', ScheduleApproval::STATUS_PENDING);
    }

    public function statusBadge(): string
    {
        return [
            self::STATUS_DRAFT => 'secondary',
            self::STATUS_PENDING_APPROVAL => 'warning',
            self::STATUS_APPROVED => 'info',
            self::STATUS_OTP_SENT => 'primary',
            self::STATUS_QUEUED => 'info',
            self::STATUS_PROCESSING => 'primary',
            self::STATUS_PAID => 'success',
            self::STATUS_PARTIALLY_PAID => 'warning',
            self::STATUS_FAILED => 'danger',
            self::STATUS_CANCELLED => 'dark',
        ][$this->status] ?? 'light';
    }
}
