<?php

namespace Modules\Demarcations\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Demarcation extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function history(): HasMany
    {
        return $this->hasMany(DemarcationHistory::class, 'foreign_key', 'local_key');
    }

    
}
