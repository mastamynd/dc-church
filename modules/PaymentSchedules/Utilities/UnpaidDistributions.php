<?php

namespace Modules\PaymentSchedules\Utilities;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Church\Models\PaymentDivision;
use Modules\PaymentSchedules\Models\PaymentSchedule;

class UnpaidDistributions
{
    public const TYPES = ['region', 'sub_region'];

    public static function lockedDivisionIds(): array
    {
        if (!Schema::hasTable('payment_schedule_item_division') || !Schema::hasTable('payment_schedules')) {
            return [];
        }

        return DB::table('payment_schedule_item_division as pivot')
            ->join('payment_schedule_items as items', 'items.id', '=', 'pivot.schedule_item_id')
            ->join('payment_schedules as schedules', 'schedules.id', '=', 'items.payment_schedule_id')
            ->whereNull('items.deleted_at')
            ->whereNull('schedules.deleted_at')
            ->whereIn('schedules.status', PaymentSchedule::ACTIVE_STATUSES)
            ->pluck('pivot.payment_division_id')
            ->map(function ($id) {
                return (int) $id;
            })
            ->unique()
            ->values()
            ->all();
    }

    public static function unpaidQuery(string $type)
    {
        $query = PaymentDivision::query()
            ->where('payment_divisions.type', $type)
            ->whereNull('payment_divisions.payment_date');

        $locked = self::lockedDivisionIds();

        if ($locked !== []) {
            $query->whereNotIn('payment_divisions.id', $locked);
        }

        return $query;
    }

    public static function groupedByDemarcation(string $type, ?int $regionId = null): Collection
    {
        $column = $type === 'region' ? 'regional_id' : 'sub_regional_id';
        $prefix = DB::getTablePrefix();

        $query = self::unpaidQuery($type)
            ->join('demarcations as d', 'payment_divisions.' . $column, '=', 'd.id')
            ->leftJoin('banks as b', function ($join) {
                $join->on('b.id', '=', 'd.bank_id')->whereNull('b.deleted_at');
            })
            ->selectRaw(
                $prefix . 'd.id as demarcation_id, ' .
                $prefix . 'd.name, ' .
                $prefix . 'd.paybill, ' .
                $prefix . 'd.account_number, ' .
                $prefix . 'd.bank_id, ' .
                $prefix . 'b.name as bank_name, ' .
                'SUM(' . $prefix . 'payment_divisions.amount) as total, ' .
                'SUM(' . $prefix . 'payment_divisions.topup) as topup, ' .
                'COUNT(' . $prefix . 'payment_divisions.id) as division_count'
            )
            ->groupBy('d.id')
            ->orderBy('d.name');

        if ($type === 'sub_region' && $regionId) {
            $query->where('payment_divisions.regional_id', $regionId);
        }

        if ($type === 'region' && $regionId) {
            $query->where('payment_divisions.regional_id', $regionId);
        }

        return $query->get();
    }

    public static function excludeLocked($query)
    {
        $locked = self::lockedDivisionIds();

        if ($locked === []) {
            return $query;
        }

        $table = (new PaymentDivision())->getTable();

        return $query->whereNotIn($table . '.id', $locked);
    }

    public static function unpaidForDemarcation(string $type, int $demarcationId)
    {
        $column = $type === 'region' ? 'regional_id' : 'sub_regional_id';

        return self::unpaidQuery($type)->where('payment_divisions.' . $column, $demarcationId);
    }

    public static function churchLines(string $type, int $demarcationId)
    {
        $column = $type === 'region' ? 'regional_id' : 'sub_regional_id';
        $prefix = DB::getTablePrefix();

        return self::unpaidQuery($type)
            ->leftJoin('churches as c', 'c.id', '=', 'payment_divisions.church_id')
            ->where('payment_divisions.' . $column, $demarcationId)
            ->selectRaw(
                $prefix . 'c.name as church_name, ' .
                $prefix . 'payment_divisions.month, ' .
                $prefix . 'payment_divisions.year, ' .
                'SUM(' . $prefix . 'payment_divisions.amount) as total, ' .
                'SUM(' . $prefix . 'payment_divisions.topup) as topup'
            )
            ->groupBy('payment_divisions.church_id', 'payment_divisions.month', 'payment_divisions.year')
            ->orderBy('c.name')
            ->orderBy('payment_divisions.year')
            ->orderBy('payment_divisions.month')
            ->get();
    }
}
