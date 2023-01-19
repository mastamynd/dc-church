@extends('layouts.admin')

@section('title', trans('church::general.national_report').' '. $takeyear)
<style>
    .text-bold {
        font-weight: 800 !important;
    }
</style>

@section('new_button')

<!-- <a href="{{ route('customers.export', request()->input()) }}" class="btn btn-white btn-sm">{{ trans('general.export') }}</a> -->
<a href="{{ route('church.regionalPaymentDivisionSummary',['year'=>$takeyear,'region_id'=>$region_id]) }}" class="btn btn-white btn-sm">{{ trans_choice('church::general.regional_report',1) }}</a>
<a href="{{ route('church.subRegionPaymentDivisionSummary',['year'=>$takeyear,'region_id'=>$region_id,'sub_region_id'=>$sub_region_id]) }}" class="btn btn-white btn-sm">{{ trans('church::general.national_report_summary') }}</a>
<a href="{{route('church.payment-Division-Summary-Index-Print',['year'=>$takeyear,'region_id'=>$region_id,'sub_region_id'=>$sub_region_id])}}" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-regular fa-print"></i> {{ trans('church::general.print') }} </a>

@endsection

@section('content')
@if ($response)
<div class="card-header form-fields mb-5">
    <form action="{{ route('church.paymentDivisionSummaryIndex') }}" method="get">
        <div class="row">
            <div class="form-group form-group-lg col-md-3">
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
                        <option value="{{ $r }}" {{$r == $takeyear ? 'selected' : '' }}>{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group form-group-lg col-md-3">
                <label class="control-label">{{ trans('church::general.select_region') }}</label>
                <div class="input-group input-group-merge ">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-solid fa-calendar"></i>
                        </span>
                    </div>
                    <select name="region_id" placeholder="Select {{ trans('church::general.select_region') }}..." class="form-control region_id_field selectize-field" data-searchfield="region_id" data-labelfield="region_id" data-valuefield="region_id">
                        <option value="0">Select Region...</option>
                        <option value="all">All</option>
                        @foreach ($regions as $r)
                        <option value="{{ $r->id }}" {{$r->id == $region_id ? 'selected' : '' }}>{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group form-group-lg col-md-3">
                <label class="control-label">{{ trans('church::general.select_sub_region') }}</label>
                <div class="input-group input-group-merge ">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-solid fa-calendar"></i>
                        </span>
                    </div>
                    <select name="sub_region_id" placeholder="Select {{ trans('church::general.select_sub_region') }}..." class="form-control sub_region_id_field" data-searchfield="sub_region_id" data-labelfield="sub_region_id" data-valuefield="sub_region_id">
                        <option value="0">Select Sub Region...</option>
                        <option value="all">All</option>

                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-lg filter-submit float-right mt-4"><i class="fas fa-solid fa-filter"></i></button>
            </div>
        </div>
    </form>
</div>

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
                    <td>{{ number_format($item['january'],2)}}</td>
                    <td>{{number_format($item['february'],2)}}</td>
                    <td>{{number_format($item['march'],2)}}</td>
                    <td>{{number_format($item['april'],2)}}</td>
                    <td>{{number_format($item['may'],2)}}</td>
                    <td>{{number_format($item['june'],2)}}</td>
                    <td>{{number_format($item['july'],2)}}</td>
                    <td>{{ number_format($item['august'],2)}}</td>
                    <td>{{number_format($item['september'],2)}}</td>
                    <td>{{number_format($item['october'],2)}}</td>
                    <td>{{number_format($item['november'],2)}}</td>
                    <td>{{number_format($item['december'],2)}}</td>
                    <td>{{number_format($item['total'],2)}}</td>
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
<div class="hidden subregion-field" data-subregion="{{$sub_region_id}}" ></div>
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
        $('.region_id_field').on('change', (e) => {
            var value = $(e.currentTarget).val();
            if(value > 0){
                $.ajax({
                    url: `{{route('church.getSubregionsByRegionID')}}`,
                    method: 'GET',
                    data: {
                        "region_id": value,
                    },
                    success: (data) => {
                        $('.sub_region_id_field').empty();
                        var option_ = `<option value="0">Select Sub Region...</option>
                            <option value="all">All</option>`;
                        $('.sub_region_id_field').append(option_);
                        const selected_sub = $('.subregion-field').data('subregion');
                        console.log(parseInt(selected_sub))
                        $.each(data, (i, obj) => {
                            // console.log(obj.dermacation_id);
                            var option = `<option value="${obj.demarcation_id}" ${obj.demarcation_id == parseInt(selected_sub) ? 'selected' : ''}>${obj.demarcation.name}</option>`
                            $('.sub_region_id_field').append(option);
                        });
    
                       
                    }
    
                })

                console.log(value)
            }
        })
        $('.region_id_field').trigger('change');
    });
</script>
@endpush