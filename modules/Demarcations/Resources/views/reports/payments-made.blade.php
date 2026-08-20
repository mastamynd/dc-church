@extends('layouts.admin')

@section('title', trans_choice('demarcations::general.payments_made', 1))
<?php
	$fullURL = request()->fullUrl();
	$hasQ = count(explode('?', $fullURL)) > 1;		

?>
@section('new_button')
	<a href="{{ $fullURL.($hasQ ? '&' : '?') }}export" class="btn btn-white btn-sm">{{ trans('general.export') }}</a>
	<a href="{{ route('demarcations.payments.pending') }}" class="btn btn-success btn-sm">Make Payments</a>
	<a href="{{ route('demarcations.make-payments-new') }}" class="btn btn-primary btn-sm">Make Payments <small>NEW</small></a>
@endsection

@section('content')
	@if ($regions->count() || request()->get('search', false))
		<div class="card">
			<div class="table-responsive custom">
				<table class="table table-flush table-hover">
					<thead class="thead-light">
						<tr class="table-head-line">
							<th>@sortablelink('name', 'Name')</th>
							<th>@sortablelink('overseer', 'Overseer')</th>
							<th>@sortablelink('to_pay', 'Payable')</th>
							<th>@sortablelink('paid', 'Paid')</th>
							<th>@sortablelink('paid', 'Pending')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($regions as $item)
							<tr class="align-items-center border-top-1">
								<td><a href="{{ route('demarcations.payment-made-show', [$item->type, $item->id]) }}">{{ $item->name }}</a></td>
								<td>{{ isset($item->overseer) ? (isset($item->overseer->pastor) ? $item->overseer->pastor->contact->name : 'N/A' ) : 'N/A' }}</td>
								<td>@money($item->amounts('received'), setting('default.currency'), true)</td>
								<td>@money($item->amounts('paid'), setting('default.currency'), true)</td>
								<td>@money(($item->amounts('received')-$item->amounts('paid')), setting('default.currency'), true)</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
		<div class="alert alert-info">
			<i class="fas fa-information-circle"></i> Module under maintenance 
		</div>
	@else
		<x-empty-page group="banking" page="transfers" textPage="{{ trans('demarcations::general.tithe') }}" routeCreate="{{ 'payment-reception.receive-tithe' }}" textEmptyPage="{{ trans('demarcations::general.no_records') }}" />
	@endif
@endsection

@push('scripts_start')
	<script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script>
@endpush
