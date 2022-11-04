@extends('layouts.admin')

@section('title', trans('church::general.yearly_report').' '. $year)
<style>
    .text-bold {
        font-weight: 800 !important;
    }

    .filter-submit {
        margin-top: 50% !important;
    }
</style>

@section('new_button')


<a href="{{route('church.yearly-returns-records-print',['year'=>$year])}}" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-regular fa-print"></i> {{ trans('church::general.print') }} </a>
<a href="{{route('church.yearly-returns-record-export',['year'=>$year])}}" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-file-excel"></i> {{ trans('church::general.export_excel') }} </a>


@endsection

@section('content')
<div class="card-header mb-5">
    <form action="{{route('church.yearly-returns-records')}}" method="get">
        <div class="p-2 row">

            <div class="form-group form-group-lg col-md-11 ">
                <label class="control-label">{{ trans('church::general.select_year') }}</label>
                <div class="input-group input-group-merge ">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-solid fa-calendar"></i>
                        </span>
                    </div>
                    <select name="year" placeholder="Select {{ trans('church::general.select_year') }}..." class="form-control selectize-field" data-searchfield="year" data-labelfield="year" data-valuefield="year">
                        <option>Select Year...</option>
                        @foreach ($selected_years as $r)
                        <option value="{{ $r }}" {{$r == $year ? 'selected' : '' }}>{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-lg filter-submit float-right"><i class="fas fa-solid fa-filter"></i></button>
            </div>
        </div>
    </form>
</div>

<div class="card" id="printableArea">
    <div class="card-header">
        <h5>{{trans('church::general.yearly_report').' '. $year}}</h5>
    </div>

    <div class="row p-3">
        <div class="col-md-8">
            <div class="table-responsive custom">
                <table class="table table-flush table-bordered table-hover">
                    <thead class="thead-light">
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
                            <td>{{number_format($item[$column],2)}}</td>
                            @endforeach
                            <td>{{number_format($item['total_amount'],2)}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <div id="chartContainer" data-graphdata="{{json_encode($graph)}}" class="mt-5" style="height: 300px; width: 100%;"></div>
        </div>
    </div>

</div>

@endsection

@push('scripts_start')
<!-- <script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css" integrity="sha512-bkB9w//jjNUnYbUpATZQCJu2khobZXvLP5GZ8jhltg7P/dghIrTaSJ7B/zdlBUT0W/LXGZ7FfCIqNvXjWKqCYA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.bootstrap4.min.css" integrity="sha512-MMojOrCQrqLg4Iarid2YMYyZ7pzjPeXKRvhW9nZqLo6kPBBTuvNET9DBVWptAo/Q20Fy11EIHM5ig4WlIrJfQw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style type="text/css" rel="stylesheet">
    .selectize-dropdown,
    .selectize-input {
        line-height: 18px;
        padding: 13px;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js" integrity="sha512-pF+DNRwavWMukUv/LyzDyDMn8U2uvqYQdJN0Zvilr6DDo/56xPDZdDoyPDYZRSL4aOKO/FGKXTpzDyQJ8je8Qw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
    var selectizerNow = function(tis) {
        var $url = $(tis).data('url');
        var $valueField = $(tis).data('valuefield');
        var $labelField = $(tis).data('labelfield');
        var $searchField = $(tis).data('searchfield');
        var $icon = $(tis).data('icon');
        var locked = $(this).attr('readonly') == 'true';

        if ($url) {
            $(tis).selectize({
                valueField: $valueField,
                labelField: $labelField,
                searchField: $searchField,
                create: false,
                render: {
                    option: function(item, escape) {
                        return (
                            `<div class="p-2 pt-3 pb-3"><i class="far ${$icon}"></i> ${item[$labelField]}</div>`
                        );
                    },
                },
                load: function(query, callback) {
                    if (!query.length) return callback();
                    var $voucherType = $('select[name="payment_voucher_type"]').val();

                    $.ajax({
                        url: $url + encodeURIComponent(query),
                        type: "GET",
                        data: {
                            'voucher_type': $voucherType
                        },
                        error: function() {
                            callback();
                        },
                        success: function(res) {
                            callback(res);
                        },
                    });
                },
            });
        } else {
            $(tis).selectize();
        }

        if (locked) {
            tis[0].selectize.lock();
        }
    }

    $(function() {
        $(this).find('.selectize-field').each(function() {
            if ($(this)[0].selectize) {
                $(this)[0].selectize.destroy();
            }
            selectizerNow(this);
        });
        const graphData = $('#chartContainer').data('graphdata');
        var chart = new CanvasJS.Chart("chartContainer",
	{
		title:{
			text: "Yearly Returns Chart"
		},
		legend: {
			maxWidth: 350,
			itemWidth: 120
		},
		data: [
		{
			type: "pie",
			showInLegend: true,
			legendText: "{indexLabel}",
			dataPoints: graphData
		}
		]
	});
	chart.render();


    });
</script>
@endpush