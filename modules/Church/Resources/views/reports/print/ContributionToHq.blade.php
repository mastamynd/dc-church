<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print {{trans('church::general.contribution_to_hq').' '. $year}} </title>
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
    <div class="card border-0">
        <div class="card-header text-center">
            <h5>{{trans('church::general.contribution_to_hq').' '. $year}}</h5>
        </div>
        <div class="card-body">
        @foreach($response as $region)
        <div class="card" style="box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 5px 0px, rgba(0, 0, 0, 0.1) 0px 0px 1px 0px;">
            <div class="card-header">
                <h5>{{trans('church::general.sub_to_hq_section').' '. $region->name}}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive custom">
                    <table class="table table-flush table-hover">
                        <thead class="thead-light">
                            <tr class="table-head-line">
                                <th>{{ trans('church::general.name') }}</th>
                                <th>{{ trans('church::general.jan') }}</th>
                                <th>{{ trans('church::general.feb') }}</th>
                                <th>{{ trans('church::general.mar') }}</th>
                                <th>{{ trans('church::general.apr') }}</th>
                                <th>{{ trans('church::general.may') }}</th>
                                <th>{{ trans('church::general.jun') }}</th>
                                <th>{{ trans('church::general.jul') }}</th>
                                <th>{{ trans('church::general.aug') }}</th>
                                <th>{{ trans('church::general.sep') }}</th>
                                <th>{{ trans('church::general.oct') }}</th>
                                <th>{{ trans('church::general.nov') }}</th>
                                <th>{{ trans('church::general.dec') }}</th>
                               
                            </tr>
                        </thead>
    
                        <tbody>
                            @foreach($region['churches'] as $item)
                                <tr>
                                    <td>{{$item->church_name}}</td>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
        </div>


    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>