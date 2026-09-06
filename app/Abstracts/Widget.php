<?php

namespace App\Abstracts;

use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Traits\Charts;
use App\Utilities\Date;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

abstract class Widget
{
    use Charts;

    public $model;

    public $default_name = '';

    public $default_settings = [
        'width' => 'col-md-4',
    ];

    public $views = [
        'header' => 'partials.widgets.standard_header',
    ];

    public function __construct($model = null)
    {
        $this->model = $model;
    }

    public function getDefaultName()
    {
        return trans($this->default_name);
    }

    public function getDefaultSettings()
    {
        return $this->default_settings;
    }

    public function getViews()
    {
        return $this->views;
    }

    public function view($name, $data = [])
    {
        if (request()->isApi()) {
            return $data;
        }

        return view($name, array_merge(['class' => $this], (array) $data));
    }

    public function applyFilters($model, $args = ['date_field' => 'paid_at'])
    {
        if (empty(request()->get('start_date', null))) {
            return $model;
        }

        $start_date = request()->get('start_date') . ' 00:00:00';
        $end_date = request()->get('end_date') . ' 23:59:59';

        return $model->whereBetween($args['date_field'], [$start_date, $end_date]);
    }

    public function calculateDocumentTotals($model)
    {
        $open = $overdue = 0;

        $today = Date::today()->toDateString();

        if ($model->status == 'paid') {
            return [$open, $overdue];
        }

        $payments = 0;

        if ($model->status == 'partial') {
            foreach ($model->transactions as $transaction) {
                $payments += $transaction->getAmountConvertedToDefault();
            }
        }

        // Check if the invoice/bill is open or overdue
        if ($model->due_at > $today) {
            $open += $model->getAmountConvertedToDefault() - $payments;
        } else {
            $overdue += $model->getAmountConvertedToDefault() - $payments;
        }

        return [$open, $overdue];
    }

    /**
     * Cache computed widget data for the active company and date range.
     *
     * @param  mixed  $ttl
     * @return mixed
     */
    protected function remember(string $key, \Closure $callback, $ttl = 60)
    {
        return Cache::remember($this->cacheKey($key), $ttl, $callback);
    }

    protected function cacheKey(string $key): string
    {
        return implode('.', [
            'widgets',
            company_id(),
            str_replace('\\', '_', static::class),
            $key,
            request('start_date'),
            request('end_date'),
        ]);
    }

    protected function rawTable(string $table): string
    {
        return DB::getTablePrefix() . $table;
    }

    /**
     * Sum amounts converted to the default currency in SQL.
     */
    protected function sumConvertedToDefault($query, ?string $table = null): float
    {
        $model = $query->getModel();
        $table = $table ?: $model->getConnection()->getTablePrefix() . $model->getTable();

        return (float) $query->sum(DB::raw($table . '.amount / NULLIF(' . $table . '.currency_rate, 0)'));
    }

    /**
     * Open vs overdue document totals without hydrating models.
     *
     * @return array{0: float, 1: float}
     */
    protected function documentOpenAndOverdue(string $type): array
    {
        $today = Date::today()->toDateString();
        $documentTable = (new Document())->getTable();
        $transactionTable = (new Transaction())->getTable();
        $documentTableRaw = DB::getTablePrefix() . $documentTable;
        $transactionTableRaw = DB::getTablePrefix() . $transactionTable;

        $documents = $type === 'invoice'
            ? Document::invoice()->accrued()->notPaid()
            : Document::bill()->accrued()->notPaid();

        $documents = $this->applyFilters($documents, ['date_field' => $documentTable . '.created_at']);

        $paid = Transaction::query()
            ->selectRaw('document_id, SUM(' . $transactionTableRaw . '.amount / NULLIF(' . $transactionTableRaw . '.currency_rate, 0)) as total')
            ->whereIn('document_id', (clone $documents)->select($documentTable . '.id'))
            ->groupBy('document_id');

        $paidAlias = DB::getTablePrefix() . 'paid';

        $row = $documents
            ->leftJoinSub($paid, 'paid', 'paid.document_id', '=', $documentTable . '.id')
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN ' . $documentTableRaw . '.due_at > ? THEN GREATEST(' . $documentTableRaw . '.amount / NULLIF(' . $documentTableRaw . '.currency_rate, 0) - COALESCE(' . $paidAlias . '.total, 0), 0) ELSE 0 END), 0) as open_total,
                 COALESCE(SUM(CASE WHEN ' . $documentTableRaw . '.due_at <= ? THEN GREATEST(' . $documentTableRaw . '.amount / NULLIF(' . $documentTableRaw . '.currency_rate, 0) - COALESCE(' . $paidAlias . '.total, 0), 0) ELSE 0 END), 0) as overdue_total',
                [$today, $today]
            )
            ->first();

        return [
            (float) ($row->open_total ?? 0),
            (float) ($row->overdue_total ?? 0),
        ];
    }
}
