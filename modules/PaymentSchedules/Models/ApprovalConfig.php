<?php

namespace Modules\PaymentSchedules\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApprovalConfig extends Model
{
    protected $table = 'payment_schedule_approval_configs';

    protected $fillable = [
        'company_id',
        'name',
        'applies_to',
        'enabled',
        'created_from',
        'created_by',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function steps(): HasMany
    {
        return $this->hasMany(ApprovalStep::class, 'config_id')->orderBy('level');
    }

    public static function current(): ?self
    {
        return static::where('enabled', 1)->orderBy('id')->first();
    }
}
