<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print {{trans('church::general.comparison_table').' '. $year}} </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
 
        table{
            border: none !important;
            border-collapse: collapse;
            font-size: 12pt !important;
        }

        table th, table td{
            background-color: transparent !important;
            border: 1px solid #000 !important;
            color: #000;
            font-size: 12px !important;
            padding: 5px !important;
        } 

        .card-header{
            border: none !important;
            background-color: transparent;
            font-size: 13px !important;
        }

        .c-year {
            background-color: #f2dede !important;
        }

        .p-year {
            background-color: #fcf8e3 !important;
        }

        .d-years {
            background-color: #d9edf7 !important;
        }

        .g-years {
            background-color: #dff0d8 !important;
        }
    </style>

</head>

<body onload="window.print()" class="p-3">
    <div class="card border-0">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <img src="{{asset('public/img/delivarance.jpeg')}}" alt="img">
                </div>

                <div class="detail" style="text-align: right;">
                    <span class="detail-record">Delivarance Church International</span> <br>
                    <span class="detail-record">P.O BOX 12345</span> <br>
                    <span class="detail-record">00200 Nairobi</span><br>
                    <span class="detail-record">Phone : 07123456789</span><br>
                    <span class="detail-record">Email : Delivarance.Church@gmail.com</span>

                </div>
            </div>
            <h5 class="text-center" style="font-weight: 550 !important;">{{trans('church::general.comparison_table').' '. $year}}</h5>

        </div>

        <div class="card-body">
            <div class="table-responsive custom">
                <table class="table table-sm table-condensed">
                    <thead class="thead-light">
                        <tr class="table-head-line">
                            <th>{{ trans('church::general.region_name') }}</th>
                            <th>{{ trans('church::general.church_total') }}</th>
                            <th>{{ trans('church::general.remitting_total') }}</th>
                            @foreach($columns as $column)
                            <th>{{ ucwords(str_replace('_', ' ',$column)) }}</th>
                            @endforeach
                            <th class="c-year">Yr.{{$year}} Total</th>
                            <th class="p-year">Yr.{{$prev_year}} Total</th>
                            <th class="d-years">Diff</th>
                            <th class="g-years">Growth%</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($regions as $item)
                        <tr class="align-items-center border-top-1 {{$item['name'] == 'TOTAL' ? 'text-bold' : ''}}">
                            <td>{{$item->name}}</td>
                            <td>{{$item['churches_count']}}</td>
                            <td>{{$item['remitting_churches']}}</td>
                            @foreach($columns as $column)
                            <td>{{ number_format($item[$column], 2) }}</td>
                            @endforeach
                            <td class="c-year">{{ number_format($item['current_total_amount'], 2) }}</td>
                            <td class="p-year">{{ number_format($item['prev_total_amount'], 2) }}</td>
                            <td class="d-years">{{ number_format($item['difference'], 2) }}</td>
                            <td class="g-years">{{ number_format($item['growth'], 2) }} %</td>
                        </tr>
                        @endforeach
                        <tr class="align-items-center border-top-1 {{$item['name'] == 'TOTAL' ? 'text-bold' : ''}}">
                            <td>Totals</td>
                            <td>{{ number_format($totals['churches_count'],0)}}</td>
                            <td>{{number_format($totals['remitting_churches'],0)}}</td>
                            @foreach($columns as $column)
                            <td>{{ number_format($totals[$column], 2) }}</td>
                            @endforeach
                            <td class="c-year">{{number_format($totals['current_total_amount'],2)}}</td>
                            <td class="p-year">{{number_format($totals['prev_total_amount'],2) }}</td>
                            <td class="d-years">{{ $totals['difference'] }}</td>
                            <td class="g-years">{{ $totals['growth'] }} %</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>