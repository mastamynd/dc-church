<?php

namespace Modules\Church\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Demarcations\Models\Demarcation;

class ChurchHistory extends Model
{
    use HasFactory;

    protected $fillable = ['church_id', 'demarcation_id','start_date'];

    public function demarcation(){
        return $this->hasOne(Demarcation::class, 'id', 'demarcation_id');
    }

    public function church(){
        return $this->hasOne(Church::class);
    }
}
