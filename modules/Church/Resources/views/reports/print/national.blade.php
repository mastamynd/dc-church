<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print {{trans('church::general.yearly_report').' '. $takeyear}} </title>
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
    </style>

</head>

<body onload="window.print()" class="p-3">
    @if ($response)
    <div class="card">
        <div class="card-header">
            <h5>{{trans('church::general.national_report').' '. $takeyear}}</h5>
        </div>

        <div class="table-responsive custom">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="table-head-line">
                        <th class="">{{ trans('church::general.summary') }}</th>
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
                    @foreach($response as $item)
                    <tr class="align-items-center border-top-1 {{$item['name'] == 'TOTAL' ? 'text-bold' : ''}}">
                        <td>{{$item['name']}}</td>
                        <td>{{ number_format($item['january'], 2) }}</td>
                        <td>{{ number_format($item['february'], 2) }}</td>
                        <td>{{ number_format($item['march'], 2) }}</td>
                        <td>{{ number_format($item['april'], 2) }}</td>
                        <td>{{ number_format($item['may'], 2) }}</td>
                        <td>{{ number_format($item['june'], 2) }}</td>
                        <td>{{ number_format($item['july'], 2) }}</td>
                        <td>{{ number_format($item['august'], 2) }}</td>
                        <td>{{ number_format($item['september'], 2) }}</td>
                        <td>{{ number_format($item['october'], 2) }}</td>
                        <td>{{ number_format($item['november'], 2) }}</td>
                        <td>{{ number_format($item['december'], 2) }}</td>
                        <td>{{ number_format($item['total'], 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="align-items-center border-top-1 {{$item['name'] == 'TOTAL' ? 'text-bold' : ''}}">
                        <td>{{$s_total['name']}}</td>
                        <td>{{ number_format($s_total['january'],2)}}</td>
                        <td>{{number_format($s_total['february'],2)}}</td>
                        <td>{{number_format($s_total['march'],2)}}</td>
                        <td>{{number_format($s_total['april'],2)}}</td>
                        <td>{{number_format($s_total['may'],2)}}</td>
                        <td>{{number_format($s_total['june'],2)}}</td>
                        <td>{{number_format($s_total['july'],2)}}</td>
                        <td>{{ number_format($s_total['august'],2)}}</td>
                        <td>{{number_format($s_total['september'],2)}}</td>
                        <td>{{number_format($s_total['october'],2)}}</td>
                        <td>{{number_format($s_total['november'],2)}}</td>
                        <td>{{number_format($s_total['december'],2)}}</td>
                        <td>{{number_format($s_total['total'],2)}}</td>
                    </tr>
                    
                </tbody>
            </table>
        </div>


    </div>
    @else
    <tr>
        <td colspan="14" class="text-center">No Transactions Found</td>
    </tr>
    @endif
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>