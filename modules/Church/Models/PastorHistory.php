<?php

namespace Modules\Church\Models;

use App\Abstracts\Model;

class PastorHistory extends Model
{
    protected $fillable = ['rank','church_id','position','pastor_id'];

    public function pastor(){
        return $this->belongsTo(Pastor::class);
    }

    public function church(){
        return $this->belongsTo(Church::class, 'church_id');
    }

    public function rank(){
        return $this->belongsTo(Church::class, 'church_id');
    }
}
