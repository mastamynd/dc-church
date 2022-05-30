@extends('layouts.admin')

@section('title', trans_choice('general.customers', 2))

@section('new_button')
   
    <a href="{{ route('customers.export', request()->input()) }}" class="btn btn-white btn-sm">{{ trans('general.export') }}</a>
@endsection

@section('content')
    @if ($customers->count() || request()->get('search', false))
        <div class="card">
           
            <div class="table-responsive custom">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                        <tr class="table-head-line">
                            <th class="">{{ trans('church::genaral.summary') }}</th>
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
                        @foreach($customers as $item)
                            <tr class="align-items-center border-top-1">
                                
                            </tr>
                        @endforeach
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
    <script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script>
@endpush
