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
    <div class="card border-0">
        <div class="card-header text-center">
            <h5>{{trans('church::general.yearly_report').' '. $year}}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive custom">
                <table class="table table-flush table-bordered table-hover">
                    <thead class="bg-light">
                        <tr class="table-head-line">
                            <th>{{ trans('church::general.region_name') }}</th>
                            @foreach($columns as $column)
                            <th>{{ ucwords(str_replace('_', ' ',$column)) }}</th>
                            @endforeach
                            <th>{{ trans('church::general.total') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($regions as $item)
                        <tr class="align-items-center border-top-1 {{$item['name'] == 'TOTAL' ? 'text-bold' : ''}}">
                            <td>{{$item->name}}</td>

                            @foreach($columns as $column)
                            <td>{{$item[$column]}}</td>
                            @endforeach
                            <td>{{ number_format($item['total_amount'], 2) }}</td>
                        </tr>
                        @endforeach
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