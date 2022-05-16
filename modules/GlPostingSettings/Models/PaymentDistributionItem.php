<?php

namespace Modules\GlPostingSettings\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentDistributionItem extends Model
{
    use HasFactory;

    protected $fillable = [];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\GlPostingSettings\Database\Factories\PaymentDistributionItem::new();
    }
}
