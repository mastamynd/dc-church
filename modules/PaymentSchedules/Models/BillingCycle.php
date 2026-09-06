<?php

namespace Modules\PaymentSchedules\Models;

use App\Abstracts\Model;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BillingCycle extends Model
{
    protected $table = 'billing_cycles';

    protected $fillable = [
        'company_id',
        'name',
        'scheduled_date',
        'status',
        'notes',
        'created_from',
        'created_by',
    ];

    protected $dates = ['scheduled_date', 'deleted_at'];

    public $sortable = ['name', 'scheduled_date', 'status', 'created_at'];

    public const STATUS_OPEN = 'open';
    public const STATUS_CLOSED = 'closed';

    public function schedules(): HasMany
    {
        return $this->hasMany(PaymentSchedule::class, 'billing_cycle_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function statusBadge(): string
    {
        return $this->status === self::STATUS_OPEN ? 'success' : 'dark';
    }
}
