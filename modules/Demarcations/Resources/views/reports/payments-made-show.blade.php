@extends('layouts.admin')

@section('title', trans_choice('demarcations::general.payments_made', 1))
<?php
	$fullURL = request()->fullUrl();
	$hasQ = count(explode('?', $fullURL)) > 1;		

	function upc($txt){
		return ucwords(implode(" ", (explode("_", $txt))));
	}
?>
@section('new_button')
<a href="{{ $fullURL.($hasQ ? '&' : '?') }}export" class="btn btn-white btn-sm">{{ trans('general.export') }}</a>
<a href="{{ route('demarcations.payments.pending') }}" class="btn btn-success btn-sm">Make Payments</a>
@endsection

@section('content')
<div class="card">
	<div class="table-responsive custom">
		<table class="table table-flush table-hover">
			<thead class="thead-light">
				<tr class="table-head-line">
					<th>Cheque Number</th>
					<th>Amount</th>
					<th>Type</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($paymentList as $pay)
				<tr class="align-items-center border-top-1">
					<td><a href="{{ route('demarcations.cheque-number-list', [$pay->cheque_number, $pay->type]) }}">{{ $pay->cheque_number
							}}</a></td>
					<td>@money($pay->amount, setting('default.currency'), true)</td>
					<td>{{ upc($pay->type) }}</td>
					<td>{{ $pay->payment_date }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
<div class="card">
	<div class="card-header">
		{{ $type == "region" ? 'Sub Regions' : 'Churches' }}
	</div>
	<div class="table-responsive custom">
		<table class="table table-flush table-hover">
			<thead class="thead-light">
				<tr class="table-head-line">
					<th>Name</th>
					<th>{{ $type == 'region' ? 'Overseer' : 'Pastor' }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($children as $item)
				<tr class="align-items-center border-top-1">
					@if($type == 'region')
					<td>
						<a href="{{ route('demarcations.payment-made-show', [$item->demarcation->type ?? '0', $item->demarcation->id ?? 0]) }}">
							{{ $item->demarcation->name ?? 'N/A' }}</a>
					</td>
					@else
					<td>{{ $item->church->name }}</td>
					@endif
					<td>
						{{ isset($item->overseer) ? (isset($item->overseer->pastor) ? $item->overseer->pastor->contact->name :
						'N/A' ) : 'N/A' }}
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection

@push('scripts_start')
<script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script>
@endpush