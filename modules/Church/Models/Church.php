<?php

namespace Modules\Church\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PaymentReception\Models\PaymentReception;
use Modules\PaymentReception\Models\PaymentReceptionItems;

class Church extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'email', 'founded_on', 'photo', 'enabled', 'latitude', 'longitude'];

    public function pastors(){
        return $this->hasMany(PastorHistory::class)->orderBy('rank', 'desc');
    }

    public function senior_pastor(){
        return $this->hasMany(PastorHistory::class)->where('position', 'Senior')->orderBy('rank', 'desc');
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

    public function payment_division(){
        return $this->hasMany(PaymentDivision::class, 'church_id')->groupBy('church_id');
    }

    public function received_moneys(){
        return $this->hasMany(PaymentReception::class, 'church_id')->orderBy('created_at', 'desc');
    }

    public function received_money_items(){
        return $this->hasManyThrough(PaymentReceptionItems::class, PaymentReception::class);
    }
}
