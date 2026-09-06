<?php

namespace Modules\Demarcations\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    protected $table = 'banks';

    protected $fillable = ['company_id', 'name', 'code', 'enabled', 'created_from', 'created_by'];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public $sortable = ['name', 'code', 'enabled'];

    public function demarcations(): HasMany
    {
        return $this->hasMany(Demarcation::class);
    }

    public function scopeCode($query, $code)
    {
        return $query->where('code', $code);
    }
}
