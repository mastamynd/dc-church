<?php

namespace Modules\Demarcations\Models;

use App\Abstracts\Model;
use Modules\Church\Models\Pastor;
use Modules\Church\Models\PastorHistory;

class DemarcationOverseer extends Model
{
    protected $fillable = ['demarcation_id', 'pastor_id'];

    public function rank(){
        return $this->hasOne(PastorHistory::class, 'pastor_id', 'pastor_id');
    }

    public function pastor(){
        return $this->hasOne(Pastor::class, 'id', 'pastor_id');
    }
}
