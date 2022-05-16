<?php

namespace Modules\Church\Models;

use App\Abstracts\Model;

class PastorHistory extends Model
{
    protected $fillable = ['pastor_id','church_id','position','rank'];

    public function pastor(){
        return $this->belongsTo(Pastor::class);
    }
}
