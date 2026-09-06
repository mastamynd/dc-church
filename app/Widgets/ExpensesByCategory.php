<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Transaction;

class ExpensesByCategory extends Widget
{
    public $default_name = 'widgets.expenses_by_category';

    public $default_settings = [
        'width' => 'col-md-6',
    ];

    public function show()
    {
        $rows = $this->remember('categories', function () {
            $categories = $this->rawTable('categories');
            $transactions = $this->rawTable('transactions');

            return $this->applyFilters(Transaction::expense()->isNotTransfer())
                ->join('categories', function ($join) {
                    $join->on('categories.id', '=', 'transactions.category_id')
                        ->whereNull('categories.deleted_at');
                })
                ->selectRaw($categories . '.id, ' . $categories . '.name, ' . $categories . '.color, SUM(' . $transactions . '.amount / NULLIF(' . $transactions . '.currency_rate, 0)) as total')
                ->groupBy('categories.id', 'categories.name', 'categories.color')
                ->orderByDesc('total')
                ->limit(6)
                ->get()
                ->map(function ($row) {
                    return [
                        'color' => $row->color,
                        'total' => (float) $row->total,
                        'name' => $row->name,
                    ];
                })
                ->all();
        });

        foreach ($rows as $row) {
            $this->addMoneyToDonut($row['color'], $row['total'], $row['name']);
        }

        $chart = $this->getDonutChart(trans_choice('general.expenses', 2), 0, 160, 6);

        return $this->view('widgets.donut_chart', [
            'chart' => $chart,
        ]);
    }
}
