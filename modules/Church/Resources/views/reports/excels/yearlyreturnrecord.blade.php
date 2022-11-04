<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <table>
        <thead>
            <tr >
                <th>{{ trans('church::general.region_name') }}</th>
                @foreach($columns as $column)
                <th>{{ ucwords(str_replace('_', ' ',$column)) }}</th>
                @endforeach
                <th>{{ trans('church::general.total') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach($regions as $item)
            <tr>
                <td>{{$item->name}}</td>

                @foreach($columns as $column)
                <td>{{$item[$column]}}</td>
                @endforeach
                <td>{{$item['total_amount']}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>