<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <table >
        <thead class="thead-light">
            <tr class="table-head-line">
                <th>{{ trans('church::general.region_name') }}</th>
                <th>{{ trans('church::general.church_total') }}</th>
                <th>{{ trans('church::general.remitting_total') }}</th>
                @foreach($columns as $column)
                <th>{{ ucwords(str_replace('_', ' ',$column)) }}</th>
                @endforeach
                <th>Yr.{{$year}} Total</th>
                <th>Yr.{{$prev_year}} Total</th>
                <th>Diff</th>
                <th>Growth%</th>
            </tr>
        </thead>

        <tbody>
            @foreach($regions as $item)
            <tr class="align-items-center border-top-1 {{$item['name'] == 'TOTAL' ? 'text-bold' : ''}}">
                <td>{{$item->name}}</td>
                <td>{{$item['churches_count']}}</td>
                <td>{{$item['remitting_churches']}}</td>
                @foreach($columns as $column)
                <td>{{$item[$column]}}</td>
                @endforeach
                <td>{{$item['current_total_amount']}}</td>
                <td>{{ $item['prev_total_amount'] }}</td>
                <td>{{ $item['difference'] }}</td>
                <td>{{ $item['growth'] }} %</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>