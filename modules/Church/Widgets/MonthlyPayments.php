<?php

namespace Modules\Church\Widgets;

use App\Utilities\Chartjs;
use Date;
use Modules\Church\Abstracts\ChurchWidget;

class MonthlyPayments extends ChurchWidget
{
    public $default_name = 'church::widgets.monthly_payments';

    public $default_settings = [
        'width' => 'col-md-12',
    ];

    public $views = [
        'header' => 'partials.widgets.standard_header',
    ];

    public function show()
    {
        $start = Date::parse(request('start_date', Date::today()->startOfYear()->format('Y-m-d')))->startOfMonth();
        $end = Date::parse(request('end_date', Date::today()->format('Y-m-d')))->startOfMonth();

        $labels = [];
        $totals = [];
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            $key = $cursor->format('Y-m');
            $labels[] = $cursor->format('M Y');
            $totals[$key] = 0;
            $cursor->addMonth();
        }

        $rows = $this->remember('monthly', function () {
            $table = $this->paymentTableRaw();

            return $this->paymentQuery()
                ->selectRaw($table . '.year, ' . $table . '.month, SUM(' . $this->paymentAmountSql() . ') as total')
                ->groupBy($this->paymentTable() . '.year', $this->paymentTable() . '.month')
                ->get()
                ->map(function ($row) {
                    return [
                        'year' => $row->year,
                        'month' => $row->month,
                        'total' => (float) $row->total,
                    ];
                })
                ->all();
        });

        $monthNumbers = [
            'january' => '01', 'jan' => '01', '1' => '01', '01' => '01',
            'february' => '02', 'feb' => '02', '2' => '02', '02' => '02',
            'march' => '03', 'mar' => '03', '3' => '03', '03' => '03',
            'april' => '04', 'apr' => '04', '4' => '04', '04' => '04',
            'may' => '05', '5' => '05', '05' => '05',
            'june' => '06', 'jun' => '06', '6' => '06', '06' => '06',
            'july' => '07', 'jul' => '07', '7' => '07', '07' => '07',
            'august' => '08', 'aug' => '08', '8' => '08', '08' => '08',
            'september' => '09', 'sep' => '09', '9' => '09', '09' => '09',
            'october' => '10', 'oct' => '10', '10' => '10',
            'november' => '11', 'nov' => '11', '11' => '11',
            'december' => '12', 'dec' => '12', '12' => '12',
        ];

        foreach ($rows as $row) {
            $month = strtolower(trim((string) $row['month']));
            $number = $monthNumbers[$month] ?? null;

            if (!$number) {
                continue;
            }

            $key = $row['year'] . '-' . $number;

            if (isset($totals[$key])) {
                $totals[$key] += $row['total'];
            }
        }

        $chart = new Chartjs();
        $chart->type('line')
            ->width(0)
            ->height(300)
            ->options($this->getLineChartOptions())
            ->labels($labels);

        $chart->dataset(trans('church::widgets.payments_overview'), 'line', array_values($totals))
            ->backgroundColor('#328aef')
            ->color('#328aef')
            ->options([
                'borderWidth' => 4,
                'pointStyle' => 'line',
            ])
            ->fill(false);

        return $this->view('widgets.line_chart', [
            'chart' => $chart,
        ]);
    }
}
