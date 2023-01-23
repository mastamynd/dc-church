<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print {{trans('church::general.yearly_report').' '. $year}} </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        table {
            border: none !important;
            border-collapse: collapse;
            font-size: 12pt !important;
        }

        table th,
        table td {
            background-color: transparent !important;
            border: 1px solid #000 !important;
            color: #000;
            font-size: 12px !important;
            padding: 5px !important;
        }

        .card-header {
            border: none !important;
            background-color: transparent;
            font-size: 13px !important;
        }
    </style>

</head>

<body onload="window.print()" class="p-3">
    @foreach($response as $key => $report)
    <div class="card">
        <div class="card-header bg-default">
            <h4 style="color: #55588b !important;">{{$key}} {{ trans_choice('church::general.region',1)}}</h4>
        </div>
        @foreach($report['sub-region'] as $name=>$sub)
        <div class="card-header border-0 border-top-1">

            <p>
                {{$key}} {{trans_choice('church::general.region',1)}} {{trans('church::general.overseer')}} - {{$report['overseer'] ?? 'Not Set'}}
            </p>
            <p class="text-muted">
                {{$name}} {{trans_choice('church::general.subregion',1)}} {{trans('church::general.overseer')}} - {{$sub['overseer'] ?? 'Not Set'}}
            </p>


        </div>
        <div class="p-3 border-0">

            @foreach($sub['types'] as $types)
            <div class="card-header bg-light">
                <h4 style="color: #55588b !important;">{{$types['name']}}</h4>
            </div>
            <div class="table-responsive custom">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                        <tr class="table-head-line">
                            <th class="">{{ trans_choice('church::general.title',1) }}</th>
                            <th class="">{{ trans_choice('church::general.pastor',1) }}</th>
                            <th class="">{{ trans('church::general.jan') }}</th>
                            <th class="">{{ trans('church::general.feb') }}</th>
                            <th class="">{{ trans('church::general.mar') }}</th>
                            <th class="">{{ trans('church::general.apr') }}</th>
                            <th class="">{{ trans('church::general.may') }}</th>
                            <th>{{ trans('church::general.jun') }}</th>
                            <th>{{ trans('church::general.jul') }}</th>
                            <th>{{ trans('church::general.aug') }}</th>
                            <th>{{ trans('church::general.sep') }}</th>
                            <th>{{ trans('church::general.oct') }}</th>
                            <th>{{ trans('church::general.nov') }}</th>
                            <th>{{ trans('church::general.dec') }}</th>
                            <th>{{ trans('church::general.total') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($types['churches'] as $item)

                        <tr class="align-items-center border-top-1">
                            <td>{{$item['church_name']}}</td>
                            <td>{{$item->pastor_name}}</td>
                            <td>{{ number_format($item->january, 2)}}</td>
                            <td>{{ number_format($item->february, 2)}}</td>
                            <td>{{ number_format($item->march, 2)}}</td>
                            <td>{{ number_format($item->april, 2)}}</td>
                            <td>{{ number_format($item->may, 2)}}</td>
                            <td>{{ number_format($item->june, 2)}}</td>
                            <td>{{ number_format($item->july, 2)}}</td>
                            <td>{{ number_format($item->august, 2)}}</td>
                            <td>{{ number_format($item->september, 2)}}</td>
                            <td>{{ number_format($item->october, 2)}}</td>
                            <td>{{ number_format($item->november, 2)}}</td>
                            <td>{{ number_format($item->december, 2)}}</td>
                            <td>{{number_format($item->getTotalAmount(),2)}}</td>
                        </tr>



                        @endforeach
                        <tr>
                            <td>Totals</td>
                            <td>-</td>
                            <td>{{number_format($types['total']['january'],2)}}</td>
                            <td>{{number_format($types['total']['february'],2)}}</td>
                            <td>{{number_format($types['total']['march'],2)}}</td>
                            <td>{{number_format($types['total']['april'],2)}}</td>
                            <td>{{number_format($types['total']['may'],2)}}</td>
                            <td>{{number_format($types['total']['june'],2)}}</td>
                            <td>{{number_format($types['total']['july'],2)}}</td>
                            <td>{{number_format($types['total']['august'],2)}}</td>
                            <td>{{number_format($types['total']['september'],2)}}</td>
                            <td>{{number_format($types['total']['october'],2)}}</td>
                            <td>{{number_format($types['total']['november'],2)}}</td>
                            <td>{{number_format($types['total']['december'],2)}}</td>
                            <td>{{number_format($types['total']['totals'],2)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @endforeach
        </div>

        @endforeach





    </div>
    @endforeach
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>