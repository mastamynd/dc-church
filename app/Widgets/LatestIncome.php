<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Transaction;

class LatestIncome extends Widget
{
    public $default_name = 'widgets.latest_income';

    public function show()
    {
        $transactions = $this->applyFilters(
            Transaction::with('category:id,name')
                ->income()
                ->orderBy('paid_at', 'desc')
                ->isNotTransfer()
                ->select(['id', 'paid_at', 'amount', 'currency_code', 'category_id'])
                ->take(5)
        )->get();

        return $this->view('widgets.latest_income', [
            'transactions' => $transactions,
        ]);
    }
}
