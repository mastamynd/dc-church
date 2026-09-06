<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Transaction;

class TotalIncome extends Widget
{
    public $default_name = 'widgets.total_income';

    public $views = [
        'header' => 'partials.widgets.stats_header',
    ];

    public function show()
    {
        $data = $this->remember('totals', function () {
            $current = $this->sumConvertedToDefault($this->applyFilters(Transaction::income()->isNotTransfer()));
            [$open, $overdue] = $this->documentOpenAndOverdue('invoice');

            $progress = 100;

            if (!empty($open) && !empty($overdue)) {
                $progress = (int) ($open * 100) / ($open + $overdue);
            }

            return [
                'grand' => $current + $open + $overdue,
                'open' => $open,
                'overdue' => $overdue,
                'progress' => $progress,
            ];
        });

        $totals = [
            'grand'         => money($data['grand'], setting('default.currency'), true),
            'open'          => money($data['open'], setting('default.currency'), true),
            'overdue'       => money($data['overdue'], setting('default.currency'), true),
            'progress'      => $data['progress'],
        ];

        return $this->view('widgets.total_income', [
            'totals' => $totals,
        ]);
    }
}
