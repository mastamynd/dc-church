<?php

namespace Modules\Church\Widgets;

use Illuminate\Support\Facades\DB;
use Modules\Church\Abstracts\ChurchWidget;
use Modules\Church\Models\PaymentDivision;

class PaymentsBySubRegion extends ChurchWidget
{
    public $default_name = 'church::widgets.payments_by_sub_region';

    public $default_settings = [
        'width' => 'col-md-6',
    ];

    public $views = [
        'header' => 'partials.widgets.standard_header',
    ];

    public function show()
    {
        $rows = $this->remember('subregions', function () {
            $table = $this->paymentTable();

            $payments = $this->filterPayments(
                PaymentDivision::query()
                    ->join('demarcations as d', function ($join) use ($table) {
                        $join->on('d.id', '=', $table . '.sub_regional_id')
                            ->whereNull('d.deleted_at');
                    })
            )
                ->where('d.type', 'Sub Region')
                ->selectRaw($this->rawAlias('d') . '.id, ' . $this->rawAlias('d') . '.name, SUM(' . $this->paymentAmountSql() . ') as total')
                ->groupBy('d.id', 'd.name')
                ->orderByDesc('total')
                ->limit(8)
                ->get();

            $ids = $payments->pluck('id');

            $churchCounts = $ids->isEmpty()
                ? collect()
                : DB::table('church_histories')
                    ->whereNull('deleted_at')
                    ->whereIn('demarcation_id', $ids)
                    ->groupBy('demarcation_id')
                    ->selectRaw('demarcation_id, COUNT(DISTINCT church_id) as churches_count')
                    ->pluck('churches_count', 'demarcation_id');

            return $payments->map(function ($row) use ($churchCounts) {
                return [
                    'name' => $row->name,
                    'churches' => (int) ($churchCounts[$row->id] ?? 0),
                    'total' => (float) $row->total,
                ];
            })->all();
        });

        return $this->view('church::widgets.table', [
            'columns' => [
                trans_choice('church::general.subregion', 1),
                trans('church::widgets.churches'),
                trans('general.amount'),
            ],
            'rows' => collect($rows)->map(function ($row) {
                return [
                    $row['name'],
                    $row['churches'],
                    $this->formatMoney($row['total']),
                ];
            }),
            'url' => route('demarcations.subregion.index'),
            'align' => ['text-left', 'text-right', 'text-right'],
        ]);
    }
}
