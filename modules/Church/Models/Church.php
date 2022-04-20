<?php

namespace Modules\Church\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Church extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'founded_on', 'photo', 'enabled', 'latitude', 'longitude'];

    public function pastors(){
        return $this->hasMany(PastorHistory::class);
    }

    public function subregion(){
        return $this->belongsTo(ChurchHistory::class);
    }

    public function history(){
        return $this->hasMany(ChurchHistory::class)->withTrashed()->orderBy('created_at', 'desc');
    }
}
