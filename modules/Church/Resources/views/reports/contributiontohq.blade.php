@extends('layouts.admin')

@section('title', trans('church::general.contribution_to_hq').' '. $year)
<style>
    .text-bold {
        font-weight: 800 !important;
    }
</style>

@section('new_button')

<!-- <a href="{{ route('customers.export', request()->input()) }}" class="btn btn-white btn-sm">{{ trans('general.export') }}</a> -->
<!-- <a href="{{ route('church.yearly-returns-records',['year'=>$year]) }}" class="btn btn-white btn-sm">{{ trans('church::general.yearly_return') }}</a> -->


@endsection

@section('content')
<div class="card-header mb-5">
    <form action="" method="get" class="d-flex justify-content-between">
        <div class="form-group form-group-lg" style="width: 30% !important;">
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
        <div class="form-group form-group-lg" style="width: 30% !important;">
            <label class="control-label">{{ trans('church::general.select_region') }}</label>
            <div class="input-group input-group-merge ">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fas fa-solid fa-calendar"></i>
                    </span>
                </div>
                <select name="regional_id" placeholder="Select {{ trans('church::general.select_region') }}..." class="form-control selectize-field" data-searchfield="regional_id" data-labelfield="regional_id" data-valuefield="regional_id">
                    <option>Select Year...</option>
                    @foreach ($regions as $r)
                    <option value="{{ $r->id }}" {{$r->id == $regional_id ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                    <option value="all" {{$r->id == $regional_id ? 'selected' : '' }}>All</option>
                </select>
            </div>
        </div>
        <div class="footer-class">
            <button type="submit" class="btn btn-sm btn-primary mt-4">Apply Filter</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
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
                                    <td>{{$item->january}}</td>
                                    <td>{{$item->february}}</td>
                                    <td>{{$item->march}}</td>
                                    <td>{{$item->april}}</td>
                                    <td>{{$item->may}}</td>
                                    <td>{{$item->june}}</td>
                                    <td>{{$item->july}}</td>
                                    <td>{{$item->august}}</td>
                                    <td>{{$item->september}}</td>
                                    <td>{{$item->october}}</td>
                                    <td>{{$item->november}}</td>
                                    <td>{{$item->december}}</td>
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
    });
</script>
@endpush