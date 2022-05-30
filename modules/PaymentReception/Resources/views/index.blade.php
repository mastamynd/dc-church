@extends('layouts.admin')

@section('title', trans_choice('payment-reception::general.tithes_received', 1))

@section('new_button')
	@can('create-payment-reception-main')
		<a href="{{ route('payment-reception.receive-tithe') }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
	@endcan
@endsection

@section('content')
	@if ($payments->count() || request()->get('search', false))
		<div class="card">
			<div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
				{!! Form::open([
					'method' => 'GET',
					'route' => 'payment-reception.index',
					'role' => 'form',
					'class' => 'mb-0'
				]) !!}
					<div class="align-items-center" v-if="!bulk_action.show">
						<x-search-string model="Modules\PaymentReception\Models\PaymentReception" />
					</div>
				{!! Form::close() !!}
			</div>

			<div class="table-responsive">
				<table class="table table-flush table-hover">
					<thead class="thead-light">
						<tr class="row table-head-line">
							<th class="col-sm-2 col-md-1 col-lg-1 col-xl-1 d-none d-sm-block">{{ Form::bulkActionAllGroup() }}</th>
							<th class="col-xs-4 col-sm-3 col-md-4 col-lg-3 col-xl-3">@sortablelink('name', trans('general.name'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
							<th class="col-md-3 col-lg-3 col-xl-3 d-none d-md-block">@sortablelink('email', trans('general.email'))</th>
							<th class="col-lg-2 col-xl-2 d-none d-lg-block text-right">@sortablelink('unpaid', trans('general.unpaid'))</th>
							<th class="col-xs-4 col-sm-2 col-md-2 col-lg-2 col-xl-2 text-center">@sortablelink('enabled', trans('general.enabled'))</th>
							<th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center">{{ trans('general.actions') }}</th>
						</tr>
					</thead>
				</table>
			</div>

			<div class="card-footer table-action">
				<div class="row">
					@include('partials.admin.pagination', ['items' => $payments])
				</div>
			</div>
		</div>
	@else
		<x-empty-page group="banking" page="transfers" textPage="{{ trans('payment-reception::general.tithe') }}" routeCreate="{{ 'payment-reception.receive-tithe' }}" textEmptyPage="{{ trans('payment-reception::general.tithes_received_msg') }}" />
	@endif
@endsection

@push('scripts_start')
	<script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script>
@endpush
