@extends('layouts.admin')

@section('title', trans_choice('demarcations::general.payments_made', 1))

@section('content')
	@if ($payments->count() || request()->get('search', false))
		<div class="card">
			<div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
				{!! Form::open([
					'method' => 'GET',
					'route' => 'demarcations.index',
					'role' => 'form',
					'class' => 'mb-0'
				]) !!}
					<div class="align-items-center" v-if="!bulk_action.show">
						<x-search-string model="Modules\Church\Models\PaymentDivision" />
					</div>
				{!! Form::close() !!}
			</div>

			<div class="table-responsive custom">
				<table class="table table-flush table-hover">
					<thead class="thead-light">
						<tr class="table-head-line">
							<th colspan="1%">{{ Form::bulkActionAllGroup() }}</th>
							<th>@sortablelink('church.name', trans('demarcations::general.name'))</th>
							<th>To Pay</th>
							<th>Paid</th>
							<th>Pending Amount</th>
							<th>Month</th>
							<th>Year</th>
							<th>Payment Date</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($payments as $item)
							<tr class="align-items-center border-top-1">
								
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<div class="card-footer table-action">
				<div class="row">
					@include('partials.admin.pagination', ['items' => $payments])
				</div>
			</div>
		</div>
	@else
		<x-empty-page group="banking" page="transfers" textPage="{{ trans('demarcations::general.tithe') }}" routeCreate="{{ 'demarcations.receive-tithe' }}" textEmptyPage="{{ trans('demarcations::general.tithes_received_msg') }}" />
	@endif
@endsection

@push('scripts_start')
	<script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script>
@endpush
