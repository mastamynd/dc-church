<?php

namespace Modules\PaymentSchedules\Models;

use App\Abstracts\Model;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

class ScheduleOtp extends Model
{
    protected $table = 'payment_schedule_otps';

    protected $fillable = [
        'company_id',
        'payment_schedule_id',
        'user_id',
        'code_hash',
        'expires_at',
        'used_at',
        'attempts',
    ];

    protected $dates = ['expires_at', 'used_at', 'deleted_at'];

    public const MAX_ATTEMPTS = 5;
    public const TTL_MINUTES = 10;

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(PaymentSchedule::class, 'payment_schedule_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isUsable(): bool
    {
        return is_null($this->used_at)
            && $this->attempts < self::MAX_ATTEMPTS
            && $this->expires_at
            && $this->expires_at->isFuture();
    }

    public function matches(string $code): bool
    {
        return Hash::check($code, $this->code_hash);
    }
}
