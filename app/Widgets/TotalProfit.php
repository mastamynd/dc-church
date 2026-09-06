<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Transaction;

class TotalProfit extends Widget
{
    public $default_name = 'widgets.total_profit';

    public $views = [
        'header' => 'partials.widgets.stats_header',
    ];

    public function show()
    {
        $data = $this->remember('totals', function () {
            $current_income = $this->sumConvertedToDefault($this->applyFilters(Transaction::income()->isNotTransfer()));
            $current_expenses = $this->sumConvertedToDefault($this->applyFilters(Transaction::expense()->isNotTransfer()));

            [$open_invoice, $overdue_invoice] = $this->documentOpenAndOverdue('invoice');
            [$open_bill, $overdue_bill] = $this->documentOpenAndOverdue('bill');

            $current = $current_income - $current_expenses;
            $open = $open_invoice - $open_bill;
            $overdue = $overdue_invoice - $overdue_bill;

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

        return $this->view('widgets.total_profit', [
            'totals' => $totals,
        ]);
    }
}
