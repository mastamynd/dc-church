<?php

namespace Modules\Church\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Church extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'founded_on', 'photo', 'enabled', 'latitude', 'longitude'];

    public function pastors(){
        return $this->hasMany(PastorHistory::class)->orderBy('rank', 'desc');
    }

    public function subregion(){
        return $this->hasOne(ChurchHistory::class);
    }

    public function history(){
        return $this->hasMany(ChurchHistory::class)->withTrashed()->orderBy('created_at', 'desc');
    }

    public function pastor_history(){
        return $this->hasMany(PastorHistory::class)->withTrashed()->orderBy('created_at', 'desc');
    }
}
