<?php

namespace Modules\PaymentSchedules\Models;

use App\Abstracts\Model;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleApproval extends Model
{
    protected $table = 'payment_schedule_approvals';

    protected $fillable = [
        'company_id',
        'payment_schedule_id',
        'step_id',
        'level',
        'name',
        'approver_type',
        'approver_id',
        'is_final',
        'status',
        'approved_by',
        'approved_at',
        'comment',
    ];

    protected $casts = [
        'is_final' => 'boolean',
    ];

    protected $dates = ['approved_at', 'deleted_at'];

    public $tenantable = true;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(PaymentSchedule::class, 'payment_schedule_id');
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'approver_id');
    }

    public function approverName(): string
    {
        if ($this->approver_type === ApprovalStep::TYPE_ROLE) {
            return $this->role->display_name ?? $this->role->name ?? trans('general.na');
        }

        return $this->user->name ?? trans('general.na');
    }

    public function userCanAct(User $user): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }

        if ($this->approver_type === ApprovalStep::TYPE_USER) {
            return (int) $this->approver_id === (int) $user->id;
        }

        return $user->roles()->where('roles.id', (int) $this->approver_id)->exists();
    }

    public function statusBadge(): string
    {
        return [
            self::STATUS_PENDING => 'warning',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
        ][$this->status] ?? 'light';
    }
}
