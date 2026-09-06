<?php

namespace Modules\Church\Widgets;

use Illuminate\Support\Facades\DB;
use Modules\Church\Abstracts\ChurchWidget;
use Modules\Church\Models\PaymentDivision;

class PaymentsByRegion extends ChurchWidget
{
    public $default_name = 'church::widgets.payments_by_region';

    public $default_settings = [
        'width' => 'col-md-6',
    ];

    public $views = [
        'header' => 'partials.widgets.standard_header',
    ];

    public function show()
    {
        $rows = $this->remember('regions', function () {
            $table = $this->paymentTable();

            $payments = $this->filterPayments(
                PaymentDivision::query()
                    ->join('demarcations as d', function ($join) use ($table) {
                        $join->on('d.id', '=', $table . '.regional_id')
                            ->whereNull('d.deleted_at');
                    })
            )
                ->where('d.type', 'Region')
                ->selectRaw($this->rawAlias('d') . '.id, ' . $this->rawAlias('d') . '.name, SUM(' . $this->paymentAmountSql() . ') as total')
                ->groupBy('d.id', 'd.name')
                ->orderByDesc('total')
                ->limit(8)
                ->get();

            $ids = $payments->pluck('id');

            $churchCounts = $ids->isEmpty()
                ? collect()
                : DB::table('church_histories as ch')
                    ->join('demarcation_histories as dh', 'dh.demarcation_id', '=', 'ch.demarcation_id')
                    ->whereNull('ch.deleted_at')
                    ->whereNull('dh.deleted_at')
                    ->whereIn('dh.parent_id', $ids)
                    ->groupBy('dh.parent_id')
                    ->selectRaw($this->rawAlias('dh') . '.parent_id, COUNT(DISTINCT ' . $this->rawAlias('ch') . '.church_id) as churches_count')
                    ->pluck('churches_count', 'parent_id');

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
                trans_choice('church::general.region', 1),
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
            'url' => route('demarcations.region.index'),
            'align' => ['text-left', 'text-right', 'text-right'],
        ]);
    }
}
