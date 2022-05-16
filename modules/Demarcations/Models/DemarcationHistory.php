<?php

namespace Modules\Demarcations\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DemarcationHistory extends Model
{
    use HasFactory;

    protected $fillable = [];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function parent(){
        return $this->belongsTo(Demarcation::class, 'parent_id');
    }
    public function demarcation(){
        return $this->belongsTo(Demarcation::class, 'demarcation_id');
    }
}
