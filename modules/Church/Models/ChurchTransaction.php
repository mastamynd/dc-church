<?php

namespace Modules\Church\Models;

use App\Abstracts\Model;
use App\Models\Banking\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChurchTransaction extends Model
{
    use HasFactory;

    protected $fillable = [];

   public function transactions(): HasMany
   {
       return $this->hasMany(Transaction::class);
   }

   public function church(): BelongsTo{
       return $this->belongsTo(Church::class);
   }
}
