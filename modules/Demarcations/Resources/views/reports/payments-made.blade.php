@extends('layouts.admin')

@section('title', trans_choice('demarcations::general.payments_made', 1))

@section('content')
	@if ($payments->count() || request()->get('search', false))
		<div class="card">
			<div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
				{!! Form::open([
					'method' => 'GET',
					'route' => 'demarcations.payments-made',
					'role' => 'form',
					'class' => 'mb-0'
				]) !!}
					<div class="align-items-center" v-if="!bulk_action.show">
						<x-search-string model="Modules\Church\Models\PaymentMadeView" />
					</div>
				{!! Form::close() !!}
			</div>

			<div class="table-responsive custom">
				<table class="table table-flush table-hover">
					<thead class="thead-light">
						<tr class="table-head-line">
							<th>@sortablelink('name', trans('demarcations::general.name'))</th>
							<th>@sortablelink('to_pay', 'Payable')</th>
							<th>@sortablelink('paid', 'Paid')</th>
							<th>@sortablelink('paid', 'Pending')</th>
							<th>@sortablelink('month', 'Month')</th>
							<th>@sortablelink('year', 'Year')</th>
							<th>@sortablelink('payment_date', 'Date')</th>
							<th>@sortablelink('type', 'Type')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($payments as $item)
							@php($payment_date = \Carbon\Carbon::parse($item->payment_date))
							<tr class="align-items-center border-top-1">
								<td>{{ $item->name }}</td>
								<td>@money($item->to_pay, setting('default.currency'), true)</td>
								<td>@money($item->paid, setting('default.currency'), true)</td>
								<td>@money(($item->to_pay-$item->paid), setting('default.currency'), true)</td>
								<td>{{ $item->month }}</td>
								<td>{{ $item->year }}</td>
								<td>{{ $item->payment_date }}</td>
								<td>{{ $item->type }}</td>
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
		<x-empty-page group="banking" page="transfers" textPage="{{ trans('demarcations::general.tithe') }}" routeCreate="{{ 'payment-reception.receive-tithe' }}" textEmptyPage="{{ trans('demarcations::general.no_records') }}" />
	@endif
@endsection

@push('scripts_start')
	<script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script>
@endpush
