<?php

namespace Modules\Church\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
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

    public function scopeForPicker($query, $search = null)
    {
        $prefix = DB::getTablePrefix();

        $latestHistory = DB::table('church_histories')
            ->select('church_id', DB::raw('MAX(id) as id'))
            ->whereNull('deleted_at')
            ->groupBy('church_id');

        $query
            ->leftJoinSub($latestHistory, 'ch_cur', 'ch_cur.church_id', '=', 'churches.id')
            ->leftJoin('church_histories as ch', 'ch.id', '=', 'ch_cur.id')
            ->leftJoin('demarcations as d', function ($join) {
                $join->on('d.id', '=', 'ch.demarcation_id')
                    ->whereNull('d.deleted_at');
            })
            ->select([
                'churches.id',
                'churches.name',
                'churches.code',
                DB::raw('CONCAT(' . $prefix . 'churches.code, " - ", ' . $prefix . 'churches.name, ", ", COALESCE(' . $prefix . 'd.name, "")) as codename'),
            ]);

        $search = trim((string) $search);

        if ($search !== '') {
            $query->where(function ($orW) use ($search) {
                $orW->where('churches.name', 'like', '%' . $search . '%')
                    ->orWhere('churches.code', 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy('churches.name');
    }
}
