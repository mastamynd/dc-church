<?php

namespace Modules\Church\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Demarcations\Models\Demarcation;

class ChurchHistory extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function demarcation(){
        return $this->belongsTo(Demarcation::class);
    }

    public function church(){
        return $this->belongsTo(Church::class);
    }
}
