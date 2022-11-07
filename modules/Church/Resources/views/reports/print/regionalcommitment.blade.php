<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print {{trans('church::general.yearly_report').' '. $year}} </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        table{
            border: none !important;
            border-collapse: collapse;
        }

        table th, table td{
            border: 1px solid #000;
        } 
    </style>

</head>

<body onload="window.print()" class="p-3">
    <div class="card">
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
            <h5 class="text-center" style="font-weight: 550 !important;">{{trans('church::general.regional_commitment').' '. $year}}</h5>
            
        </div>

        <div class="table-responsive custom">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="table-head-line">
                        <th>{{ trans('church::general.region_name') }}</th>
                        <th>{{ trans('church::general.church_total') }} ({{$year}})</th>
                        <th>{{ trans('church::general.remitting_total') }} ({{$year}})</th>
                        <th>Commitment % ({{$year}})</th>
                        <th>Commitment % ({{$prev_year}})</th>

                    </tr>
                </thead>

                <tbody>
                    @foreach($regions as $item)
                    <tr class="align-items-center border-top-1 {{$item['name'] == 'TOTAL' ? 'text-bold' : ''}}">
                        <td>{{$item->name}}</td>
                        <td>{{$item['churches_count']}}</td>
                        <td>{{$item['current_remitting_churches']}}</td>
                        <td>{{ round($item['current_commitment_perc'],2)}} %</td>
                        <td>{{ $item['prev_commitment_perc'] }} %</td>
                    </tr>
                    @endforeach
                    <tr class="align-items-center border-top-1 {{$item['name'] == 'TOTAL' ? 'text-bold' : ''}}">
                    <td>Totals</td>
                    <td>{{ number_format($totals['churches_count'],0)}}</td>
                    <td>{{ number_format($totals['current_remitting_churches'],0)}}</td>
                    <td>{{ round($totals['current_commitment_perc'],2)}} %</td>
                    <td>{{ number_format($totals['prev_commitment_perc'],2) }} %</td>
                </tr>
                </tbody>
            </table>
        </div>


    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>