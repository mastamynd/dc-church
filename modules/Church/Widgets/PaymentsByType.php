<?php

namespace Modules\Church\Widgets;

use Modules\Church\Abstracts\ChurchWidget;

class PaymentsByType extends ChurchWidget
{
    public $default_name = 'church::widgets.payments_by_type';

    public $default_settings = [
        'width' => 'col-md-6',
    ];

    public $views = [
        'header' => 'partials.widgets.standard_header',
    ];

    public function show()
    {
        $rows = $this->remember('types', function () {
            return $this->paymentQuery()
                ->selectRaw('type, SUM(' . $this->paymentAmountSql() . ') as total')
                ->groupBy('type')
                ->orderByDesc('total')
                ->limit(6)
                ->pluck('total', 'type')
                ->all();
        });

        $names = $this->paymentDivisionNames();
        $colors = ['#328aef', '#6da252', '#ef3232', '#f59e0b', '#8b5cf6', '#14b8a6', '#ec4899'];
        $index = 0;

        foreach ($rows as $type => $total) {
            if ((float) $total <= 0) {
                continue;
            }

            $label = $names[$type] ?? str_replace('_', ' ', (string) $type);
            $this->addMoneyToDonut($colors[$index % count($colors)], (float) $total, $label);
            $index++;
        }

        $chart = $this->getDonutChart(trans('church::widgets.payments_by_type'), 0, 160, 6);

        return $this->view('widgets.donut_chart', [
            'chart' => $chart,
        ]);
    }
}
