<?php

namespace Modules\PaymentSchedules\Models;

use App\Abstracts\Model;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalStep extends Model
{
    protected $table = 'payment_schedule_approval_steps';

    protected $fillable = [
        'company_id',
        'config_id',
        'level',
        'name',
        'approver_type',
        'approver_id',
        'is_final',
    ];

    protected $casts = [
        'is_final' => 'boolean',
    ];

    public const TYPE_USER = 'user';
    public const TYPE_ROLE = 'role';

    public function config(): BelongsTo
    {
        return $this->belongsTo(ApprovalConfig::class, 'config_id');
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
        if ($this->approver_type === self::TYPE_ROLE) {
            return $this->role->display_name ?? $this->role->name ?? trans('general.na');
        }

        return $this->user->name ?? trans('general.na');
    }
}
