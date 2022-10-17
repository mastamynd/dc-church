@extends('layouts.admin')

@section('title', trans_choice('church::general.regional_report',2).' '. $takeyear)
<style>
    .text-bold{
        font-weight: 800 !important;
    }
</style>

@section('new_button')
   
    <a href="#" class="btn btn-white btn-sm">{{ trans('general.print') }}</a>
   
@endsection

@section('content')
    @foreach($response as $key => $report)
        <div class="card">
            <div class="card-header bg-default" >
                <h4 style="color: white !important;">{{$key}} {{ trans_choice('church::general.region',1)}}</h4>
            </div>
            @foreach($report['sub-region'] as $name=>$sub)
                <div class="card-header border-0 border-top-1" >
                    
                    <p>
                        {{$key}} {{trans_choice('church::general.region',1)}} {{trans('church::general.overseer')}} - {{$report['overseer'] ?? 'Not Set'}} 
                    </p>
                    <p class="text-muted">
                        {{$name}} {{trans_choice('church::general.subregion',1)}} {{trans('church::general.overseer')}} - {{$sub['overseer'] ?? 'Not Set'}}
                    </p>
                          
                       
                </div>
                <div class="p-3 border-0">

                    @foreach($sub['types'] as $types)
                         <div class="card-header bg-light" >
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
                                        <td>{{$item['church_name']}}</td>
                                        <td>{{$item->pastor_name}}</td>
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
                                        <td>{{$item->total}}</td>
                                    </tr>
                                        
                                    
                                    
                                    @endforeach
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
