<?php

namespace Modules\Church\Widgets;

use Modules\Church\Abstracts\ChurchWidget;

class PastorFund extends ChurchWidget
{
    public $default_name = 'church::widgets.pastor_fund';

    public function show()
    {
        $data = $this->remember('totals', function () {
            $amount = $this->paymentAmountSql();

            $row = $this->paymentQuery()
                ->whereIn($this->paymentTable() . '.type', $this->pastorFundTypes())
                ->selectRaw('
                    COALESCE(SUM(' . $amount . '), 0) as total,
                    COALESCE(SUM(CASE WHEN payment_date IS NOT NULL THEN ' . $amount . ' ELSE 0 END), 0) as paid,
                    COALESCE(SUM(CASE WHEN payment_date IS NULL THEN ' . $amount . ' ELSE 0 END), 0) as pending
                ')
                ->first();

            return [
                'total' => (float) ($row->total ?? 0),
                'paid' => (float) ($row->paid ?? 0),
                'pending' => (float) ($row->pending ?? 0),
            ];
        });

        return $this->view('church::widgets.stats', [
            'color' => 'warning',
            'icon' => 'fa fa-gift',
            'value' => $this->formatMoney($data['total']),
            'left_label' => trans('church::widgets.paid'),
            'left_value' => $this->formatMoney($data['paid']),
            'right_label' => trans('church::widgets.pending'),
            'right_value' => $this->formatMoney($data['pending']),
            'url' => route('church.pastors.statements'),
        ]);
    }
}
