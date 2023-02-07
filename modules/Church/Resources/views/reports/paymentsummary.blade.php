@extends('layouts.admin')

@section('title', trans_choice('church::general.regional_report',2).' '. $takeyear)
<style>
    .text-bold {
        font-weight: 800 !important;
    }
</style>


@section('new_button')
<a href="{{route('church.sub-Region-Payment-Division-Summary-Print',['year'=>$takeyear,'regional_id'=>$regional_id,'sub_regional_id'=>$sub_regional_id])}}" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-regular fa-print"></i> {{ trans('church::general.print') }} </a>

@endsection

@section('content')
@foreach($response as $key => $report)
<div class="card">
    <div class="card-header bg-default">
        <h4 style="color: white !important;">{{$key}} {{ trans_choice('church::general.region',1)}}</h4>
    </div>
    @foreach($report['sub-region'] as $name=>$sub)
    <div class="card-header border-0 border-top-1">

        <p>
            {{$key}} {{trans_choice('church::general.region',1)}} {{trans('church::general.overseer')}} - {{$report['overseer'] ?? 'Not Set'}}
        </p>
        <p class="text-muted">
            {{$name}} {{trans_choice('church::general.subregion',1)}} {{trans('church::general.overseer')}} - {{$sub['overseer'] ?? 'Not Set'}}
        </p>


    </div>
    <div class="p-3 border-0">

        @foreach($sub['types'] as $types)
        <div class="card-header bg-light">
            <h4 style="color: white !important;">{{$types['name']}}</h4>
        </div>
        <div class="table-responsive custom">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="table-head-line">
                        <th class="">{{ trans_choice('church::general.title',1) }}</th>
                        <th class="">{{ trans_choice('church::general.pastor',1) }}</th>
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
                    @foreach($types['churches'] as $item)

                    <tr class="align-items-center border-top-1">
                        <td>{{$item['church_name'] ?? $item->name}}</td>
                        <td>{{$item->pastor_name ?? ''}}</td>
                        <td>{{number_format($item->january ?? 0,2)}}</td>
                        <td>{{number_format($item->february ?? 0,2)}}</td>
                        <td>{{number_format($item->march ?? 0,2)}}</td>
                        <td>{{number_format($item->april ?? 0,2)}}</td>
                        <td>{{number_format($item->may ?? 0,2)}}</td>
                        <td>{{number_format($item->june ?? 0,2)}}</td>
                        <td>{{number_format($item->july ?? 0,2)}}</td>
                        <td>{{number_format($item->august ?? 0,2)}}</td>
                        <td>{{number_format($item->september ?? 0,2)}}</td>
                        <td>{{number_format($item->october ?? 0,2)}}</td>
                        <td>{{number_format($item->november ?? 0,2)}}</td>
                        <td>{{number_format($item->december ?? 0,2)}}</td>
                        <td>{{ isset($item->january) ? number_format($item->getTotalAmount(),2) : 0}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>Totals</td>
                        <td>-</td>
                        <td>{{number_format($types['total']['january'],2)}}</td>
                        <td>{{number_format($types['total']['february'],2)}}</td>
                        <td>{{number_format($types['total']['march'],2)}}</td>
                        <td>{{number_format($types['total']['april'],2)}}</td>
                        <td>{{number_format($types['total']['may'],2)}}</td>
                        <td>{{number_format($types['total']['june'],2)}}</td>
                        <td>{{number_format($types['total']['july'],2)}}</td>
                        <td>{{number_format($types['total']['august'],2)}}</td>
                        <td>{{number_format($types['total']['september'],2)}}</td>
                        <td>{{number_format($types['total']['october'],2)}}</td>
                        <td>{{number_format($types['total']['november'],2)}}</td>
                        <td>{{number_format($types['total']['december'],2)}}</td>
                        <td>{{number_format($types['total']['totals'],2)}}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @endforeach
    </div>

    @endforeach





</div>
@endforeach

@endsection

@push('scripts_start')

@endpush