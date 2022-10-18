@extends('layouts.admin')

@section('title', trans('church::general.tithe_comparison').' '. $fromYear.' To '. $toYear )
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css" integrity="sha512-bkB9w//jjNUnYbUpATZQCJu2khobZXvLP5GZ8jhltg7P/dghIrTaSJ7B/zdlBUT0W/LXGZ7FfCIqNvXjWKqCYA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.bootstrap4.min.css" integrity="sha512-MMojOrCQrqLg4Iarid2YMYyZ7pzjPeXKRvhW9nZqLo6kPBBTuvNET9DBVWptAo/Q20Fy11EIHM5ig4WlIrJfQw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style type="text/css" rel="stylesheet">
    .selectize-dropdown,
    .selectize-input {
        line-height: 18px;
        padding: 13px;
    }
    .text-bold {
        font-weight: 800 !important;
    }
    #bar-graph{
        min-height: 70vh;
    }
</style>

@section('new_button')


<!-- <a href="{{route('church.tithe-comparison-print',['fromYear'=>$fromYear,'toYear'=>$toYear])}}" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-regular fa-print"></i> {{ trans('church::general.print') }} </a> -->


@endsection

@section('content')
<div class="card-header mb-5">
    <form action="" method="get" class="d-flex justify-content-between">
        <div class="form-group form-group-lg" style="width: 35% !important;">
            <label class="control-label">From Year</label>
            <div class="input-group input-group-merge ">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fas fa-solid fa-calendar"></i>
                    </span>
                </div>
                <select name="year" placeholder="Select {{ trans('church::general.select_year') }}..." class="form-control selectize-field" data-searchfield="year" data-labelfield="year" data-valuefield="year">
                    <option>Select Year...</option>
                    @foreach ($selected_years as $r)
                    <option value="{{ $r }}" {{$r == $fromYear ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group form-group-lg" style="width: 35% !important;">
            <label class="control-label">To Year</label>
            <div class="input-group input-group-merge ">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fas fa-solid fa-calendar"></i>
                    </span>
                </div>
                <select name="year" placeholder="Select {{ trans('church::general.select_year') }}..." class="form-control selectize-field" data-searchfield="year" data-labelfield="year" data-valuefield="year">
                    <option>Select Year...</option>
                    @foreach ($selected_years as $r)
                    <option value="{{ $r }}" {{$r == $toYear ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="footer-class">

            <button type="submit" class="btn btn-sm btn-outline-primary mt-5">Apply Fliter</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h5>{{trans('church::general.tithe_comparison').' '. $fromYear.' To '. $toYear}}</h5>
    </div>

    <div class="card">
        <div class="card-body" id="bar-graph"  data-records="{{json_encode($response)}}">
            
        </div>

    </div>


</div>

@endsection

@push('scripts_start')
<!-- <script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js" integrity="sha512-pF+DNRwavWMukUv/LyzDyDMn8U2uvqYQdJN0Zvilr6DDo/56xPDZdDoyPDYZRSL4aOKO/FGKXTpzDyQJ8je8Qw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"
    integrity="sha512-6Cwk0kyyPu8pyO9DdwyN+jcGzvZQbUzQNLI0PadCY3ikWFXW9Jkat+yrnloE63dzAKmJ1WNeryPd1yszfj7kqQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css"
    integrity="sha512-fjy4e481VEA/OTVR4+WHMlZ4wcX/+ohNWKpVfb7q+YNnOCS++4ZDn3Vi6EaA2HJ89VXARJt7VvuAKaQ/gs1CbQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.js"
    integrity="sha512-CYkt9kgBjbQrIKQGbyezfkmhmmFwMF2VVZjdGgYJJm3KnV53Ao+aFnndz2YbmMX2Y8XGBCUzBxFTqg2LAuIJzw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
        var data = $('#bar-graph').data('records'),
        config = {
            data: data,
            xkey: 'name',
            ykeys: ['value'],
            labels: ['Tithe Total'],
            fillOpacity: 0.6,
            hideHover: 'auto',
            behaveLikeLine: true,
            resize: true,
            pointFillColors: ['#ffffff'],
            pointStrokeColors: ['black'],
            lineColors: ['gray', 'red']
        };
        config.element = 'bar-graph';
        Morris.Bar(config);
    });
</script>
@endpush