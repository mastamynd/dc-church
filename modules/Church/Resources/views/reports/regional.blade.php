@extends('layouts.admin')

@section('title', trans_choice('church::general.regional_report',2).' '. $year)
<style>
    .text-bold{
        font-weight: 800 !important;
    }
</style>

@section('new_button')
   
    <a href="#" class="btn btn-white btn-sm">{{ trans('general.print') }}</a>
   
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
                                    <td>{{$item['name']}}</td>
                                    <td>{{$item['january']}}</td>
                                    <td>{{$item['february']}}</td>
                                    <td>{{$item['march']}}</td>
                                    <td>{{$item['april']}}</td>
                                    <td>{{$item['may']}}</td>
                                    <td>{{$item['june']}}</td>
                                    <td>{{$item['july']}}</td>
                                    <td>{{$item['august']}}</td>
                                    <td>{{$item['september']}}</td>
                                    <td>{{$item['october']}}</td>
                                    <td>{{$item['november']}}</td>
                                    <td>{{$item['december']}}</td>
                                    <td>{{$item['total']}}</td>
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
                                        <td>{{$item_['name']}}</td>
                                        <td>{{$item_['january']}}</td>
                                        <td>{{$item_['february']}}</td>
                                        <td>{{$item_['march']}}</td>
                                        <td>{{$item_['april']}}</td>
                                        <td>{{$item_['may']}}</td>
                                        <td>{{$item_['june']}}</td>
                                        <td>{{$item_['july']}}</td>
                                        <td>{{$item_['august']}}</td>
                                        <td>{{$item_['september']}}</td>
                                        <td>{{$item_['october']}}</td>
                                        <td>{{$item_['november']}}</td>
                                        <td>{{$item_['december']}}</td>
                                        <td>{{$item_['total']}}</td>
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
