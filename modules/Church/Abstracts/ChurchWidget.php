<?php

namespace Modules\Church\Abstracts;

use App\Abstracts\Widget;
use Illuminate\Support\Facades\DB;
use Modules\Church\Models\PaymentDivision;

abstract class ChurchWidget extends Widget
{
    public $views = [
        'header' => 'partials.widgets.stats_header',
    ];

    protected function paymentTable(): string
    {
        return (new PaymentDivision())->getTable();
    }

    protected function paymentTableRaw(): string
    {
        return DB::getTablePrefix() . $this->paymentTable();
    }

    protected function rawAlias(string $alias): string
    {
        return DB::getTablePrefix() . $alias;
    }

    protected function paymentAmountSql(?string $table = null): string
    {
        $table = $table ?: $this->paymentTableRaw();

        return 'COALESCE(' . $table . '.amount, 0) + COALESCE(' . $table . '.topup, 0)';
    }

    protected function paymentQuery()
    {
        return $this->filterPayments(PaymentDivision::query());
    }

    protected function filterPayments($query)
    {
        $start = request('start_date');
        $end = request('end_date');

        $column = 'year';

        if (method_exists($query, 'getModel')) {
            $column = $query->getModel()->qualifyColumn('year');
        }

        if (empty($start) || empty($end)) {
            return $query->where($column, date('Y'));
        }

        $startYear = (int) date('Y', strtotime($start));
        $endYear = (int) date('Y', strtotime($end));

        return $query->whereIn($column, range($startYear, $endYear));
    }

    protected function pastorFundTypes(): array
    {
        return ['pastors_fund', 'pastor_fund'];
    }

    protected function paymentDivisionNames(): array
    {
        $divisions = json_decode(setting('church.payment_divisions'), true);

        if (!is_array($divisions)) {
            return [];
        }

        $names = [];

        foreach ($divisions as $division) {
            if (!empty($division['code'])) {
                $names[$division['code']] = $division['name'] ?? $division['code'];
            }
        }

        return $names;
    }

    protected function formatMoney($amount)
    {
        return money($amount ?: 0, setting('default.currency'), true);
    }
}
