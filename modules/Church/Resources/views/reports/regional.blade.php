@extends('layouts.admin')

@section('title', trans_choice('church::general.regional_report',2).' '. $year)
<style>
    .text-bold{
        font-weight: 800 !important;
    }
</style>

@section('new_button')
   
<!-- <a href="{{route('church.regional-Payment-Division-Summary-Print',['year'=>$year])}}" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-regular fa-print"></i> {{ trans('church::general.print') }} </a> -->
   
@endsection

@section('content')
    @if($response)
        @foreach($response as $key => $report)
            <div class="card">
                <div class="card-header bg-default" >
                    <h4 style="color: white !important;">{{$key}} </h4>
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
                            @foreach($report as $k => $item)
                               @if($k != 'sub_regions') 
                               <tr class="align-items-center border-top-1 {{$item['name'] == 'TOTAL' ? 'text-bold' : ''}}">
                                    <td>{{ $item['name'] }}</td>
                                    <td>{{ number_format(floatval($item['january']), 2) }}</td>
                                    <td>{{ number_format(floatval($item['february']), 2) }}</td>
                                    <td>{{ number_format(floatval($item['march']), 2) }}</td>
                                    <td>{{ number_format(floatval($item['april']), 2) }}</td>
                                    <td>{{ number_format(floatval($item['may']), 2) }}</td>
                                    <td>{{ number_format(floatval($item['june']), 2) }}</td>
                                    <td>{{ number_format(floatval($item['july']), 2) }}</td>
                                    <td>{{ number_format(floatval($item['august']), 2) }}</td>
                                    <td>{{ number_format(floatval($item['september']), 2) }}</td>
                                    <td>{{ number_format(floatval($item['october']), 2) }}</td>
                                    <td>{{ number_format(floatval($item['november']), 2) }}</td>
                                    <td>{{ number_format(floatval($item['december']), 2) }}</td>
                                    <td>{{ number_format(floatval($item['total']), 2) }}</td>
                                </tr>
                                @endif
                             
                               
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-header bg-light mt-3 mb-3">
                    <h5>{{trans('church::general.sub_regional_report')}} </h5>
                </div>
                <div class="subs-regions p-3">

                    @foreach($report['sub_regions'] as $name=>$subs)
                    <div class="card-header bg-light mb-2">
                        <h5>{{$name}} </h5>
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
                                @foreach($subs as $l => $item_)
                                    <tr class="align-items-center border-top-1 {{$item_['name'] == 'TOTAL' ? 'text-bold' : ''}}">
                                        <td>{{ number_format($item_['name'], 2) }}</td>
                                        <td>{{ number_format($item_['january'], 2) }}</td>
                                        <td>{{ number_format($item_['february'], 2) }}</td>
                                        <td>{{ number_format($item_['march'], 2) }}</td>
                                        <td>{{ number_format($item_['april'], 2) }}</td>
                                        <td>{{ number_format($item_['may'], 2) }}</td>
                                        <td>{{ number_format($item_['june'], 2) }}</td>
                                        <td>{{ number_format($item_['july'], 2) }}</td>
                                        <td>{{ number_format($item_['august'], 2) }}</td>
                                        <td>{{ number_format($item_['september'], 2) }}</td>
                                        <td>{{ number_format($item_['october'], 2) }}</td>
                                        <td>{{ number_format($item_['november'], 2) }}</td>
                                        <td>{{ number_format($item_['december'], 2) }}</td>
                                        <td>{{ number_format($item_['total'], 2) }}</td>
                                    </tr>
                                
                                   
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                   @endforeach
                </div>

                
            </div>
        @endforeach
    @else
        <tr>
            <td colspan="14" class="text-center">No Transactions Found</td>
        </tr>
    @endif
@endsection

@push('scripts_start')
    
@endpush
