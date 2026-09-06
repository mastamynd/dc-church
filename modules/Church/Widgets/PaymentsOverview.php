<?php

namespace Modules\Church\Widgets;

use Modules\Church\Abstracts\ChurchWidget;

class PaymentsOverview extends ChurchWidget
{
    public $default_name = 'church::widgets.payments_overview';

    public function show()
    {
        $data = $this->remember('totals', function () {
            $amount = $this->paymentAmountSql();

            $row = $this->paymentQuery()
                ->selectRaw('
                    COALESCE(SUM(' . $amount . '), 0) as total,
                    COALESCE(SUM(CASE WHEN payment_date IS NOT NULL THEN ' . $amount . ' ELSE 0 END), 0) as paid,
                    COALESCE(SUM(CASE WHEN payment_date IS NULL THEN ' . $amount . ' ELSE 0 END), 0) as outstanding
                ')
                ->first();

            return [
                'total' => (float) ($row->total ?? 0),
                'paid' => (float) ($row->paid ?? 0),
                'outstanding' => (float) ($row->outstanding ?? 0),
            ];
        });

        return $this->view('church::widgets.stats', [
            'color' => 'primary',
            'icon' => 'fa fa-money-bill',
            'value' => $this->formatMoney($data['total']),
            'left_label' => trans('church::widgets.paid'),
            'left_value' => $this->formatMoney($data['paid']),
            'right_label' => trans('church::widgets.outstanding'),
            'right_value' => $this->formatMoney($data['outstanding']),
            'url' => route('church.paymentDivisionSummaryIndex'),
        ]);
    }
}
