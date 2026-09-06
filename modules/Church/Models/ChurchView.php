<?php

namespace Modules\Church\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ChurchView extends Model
{
    protected $table = 'churches';

    protected $isCachable = false;

    protected $fillable = ['name', 'code', 'company_id'];

    public $sortable = [
        'name' => 'churches.name',
        'code' => 'churches.code',
        'region' => 'r.name',
        'sub_region' => 'sr.name',
        'pastor' => 'co.name',
        'id_or_passport' => 'p.id_or_passport',
        'phone' => 'p.phone',
    ];

    protected static function booted()
    {
        static::addGlobalScope('listing', function (Builder $builder) {
            (new static)->applyListingJoins($builder);
        });
    }

    protected function applyListingJoins(Builder $query)
    {
        $churches = $this->getTable();

        $latestHistory = DB::table('church_histories')
            ->select('church_id', DB::raw('MAX(id) as id'))
            ->whereNull('deleted_at')
            ->groupBy('church_id');

        $latestDemarcation = DB::table('demarcation_histories')
            ->select('demarcation_id', DB::raw('MAX(id) as id'))
            ->whereNull('deleted_at')
            ->groupBy('demarcation_id');

        $latestSenior = DB::table('pastor_histories')
            ->select('church_id', DB::raw('MAX(id) as id'))
            ->where('rank', 'Senior')
            ->whereNull('deleted_at')
            ->groupBy('church_id');

        $query
            ->leftJoinSub($latestHistory, 'ch_cur', 'ch_cur.church_id', '=', $churches . '.id')
            ->leftJoin('church_histories as ch', 'ch.id', '=', 'ch_cur.id')
            ->leftJoinSub($latestDemarcation, 'dh_cur', 'dh_cur.demarcation_id', '=', 'ch.demarcation_id')
            ->leftJoin('demarcation_histories as dh', 'dh.id', '=', 'dh_cur.id')
            ->leftJoin('demarcations as sr', function ($join) {
                $join->on('sr.id', '=', 'ch.demarcation_id')
                    ->whereNull('sr.deleted_at');
            })
            ->leftJoin('demarcations as r', function ($join) {
                $join->on('r.id', '=', 'dh.parent_id')
                    ->whereNull('r.deleted_at');
            })
            ->leftJoinSub($latestSenior, 'ph_cur', 'ph_cur.church_id', '=', $churches . '.id')
            ->leftJoin('pastor_histories as ph', 'ph.id', '=', 'ph_cur.id')
            ->leftJoin('pastors as p', function ($join) {
                $join->on('p.id', '=', 'ph.pastor_id')
                    ->whereNull('p.deleted_at');
            })
            ->leftJoin('contacts as co', 'co.id', '=', 'p.contact_id')
            ->select([
                $churches . '.id',
                $churches . '.name',
                $churches . '.code',
                $churches . '.deleted_at',
                $this->prefixedAlias('co', 'id', 'pastor_id'),
                $this->prefixedAlias('co', 'name', 'pastor'),
                $this->prefixedAlias('p', 'id_or_passport', 'id_or_passport'),
                $this->prefixedAlias('p', 'phone', 'phone'),
                $this->prefixedAlias('ph', 'position', 'position'),
                $this->prefixedAlias('ph', 'rank', 'rank'),
                $this->prefixedAlias('sr', 'name', 'sub_region'),
                $this->prefixedAlias('r', 'name', 'region'),
                $this->prefixedAlias('r', 'id', 'region_id'),
                $this->prefixedAlias('sr', 'id', 'sub_region_id'),
            ]);
    }

    protected function prefixedAlias($alias, $column, $as)
    {
        $table = DB::getTablePrefix() . $alias;

        return DB::raw('`' . $table . '`.`' . $column . '` as `' . $as . '`');
    }
}
