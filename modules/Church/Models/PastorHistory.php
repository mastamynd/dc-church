<?php

namespace Modules\Church\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PastorHistory extends Model
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
        return \Modules\Church\Database\Factories\PastorHistory::new();
    }
}
