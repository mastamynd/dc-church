<?php

namespace Modules\Church\Widgets;

use Modules\Church\Abstracts\ChurchWidget;

class LatestPastorFund extends ChurchWidget
{
    public $default_name = 'church::widgets.latest_pastor_fund';

    public $default_settings = [
        'width' => 'col-md-6',
    ];

    public $views = [
        'header' => 'partials.widgets.standard_header',
    ];

    public function show()
    {
        $rows = $this->filterPayments(
            \Modules\Church\Models\PaymentDivision::query()
                ->leftJoin('pastors as p', 'p.id', '=', $this->paymentTable() . '.contact_id')
                ->leftJoin('contacts as co', 'co.id', '=', 'p.contact_id')
                ->leftJoin('churches as c', 'c.id', '=', $this->paymentTable() . '.church_id')
        )
            ->whereIn($this->paymentTable() . '.type', $this->pastorFundTypes())
            ->selectRaw($this->rawAlias('co') . '.name as pastor, ' . $this->rawAlias('c') . '.name as church, ' . $this->paymentAmountSql() . ' as amount, ' . $this->paymentTableRaw() . '.month, ' . $this->paymentTableRaw() . '.year')
            ->orderByDesc($this->paymentTable() . '.payment_date')
            ->orderByDesc($this->paymentTable() . '.id')
            ->take(5)
            ->get();

        return $this->view('church::widgets.table', [
            'columns' => [
                trans('church::widgets.pastor'),
                trans('church::widgets.church'),
                trans('general.amount'),
            ],
            'rows' => $rows->map(function ($row) {
                $period = trim(($row->month ?? '') . ' ' . ($row->year ?? ''));

                return [
                    $row->pastor ?: trans('general.na'),
                    $row->church ?: trans('general.na'),
                    $this->formatMoney($row->amount),
                    'meta' => $period,
                ];
            }),
            'url' => route('church.pastors.statements'),
        ]);
    }
}
