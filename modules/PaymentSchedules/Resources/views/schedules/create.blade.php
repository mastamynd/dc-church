@extends('layouts.admin')

@section('title', trans('payment-schedules::general.select_distributions') . ' — ' . $pageTitle)

@section('content')
	<form class="row mb-3" method="GET">
		<div class="col-md-4">
			<div class="form-group form-group-sm">
				<label class="form-label">{{ trans_choice('demarcations::general.region', 1) }}</label>
				<select name="region" class="form-control form-control-sm">
					<option value="">{{ trans('general.all') }}</option>
					@foreach ($regions as $region)
						<option value="{{ $region->id }}" {{ (string) request('region') === (string) $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label class="form-label">&nbsp;</label>
				<button class="btn btn-sm btn-success btn-block">{{ trans('general.search') }}</button>
			</div>
		</div>
	</form>

	<form method="POST" action="{{ route('payment-schedules.schedules.store', [$cycle->id, $type]) }}">
		@csrf
		<div class="card">
			<div class="card-header d-flex justify-content-between align-items-center">
				<div>
					<a class="btn btn-transparent btn-sm" href="{{ route('payment-schedules.cycles.show', $cycle->id) }}">
						<i class="fas fa-arrow-left"></i>
					</a>
					{{ $cycle->name }} <small><i class="fas fa-caret-right"></i> {{ $pageTitle }}</small>
				</div>
				<button type="submit" class="btn btn-primary btn-sm">
					<i class="fas fa-calendar-plus"></i> {{ trans('payment-schedules::general.new_batch') }}
				</button>
			</div>
			<div class="table-responsive custom">
				<table class="table table-flush table-hover">
					<thead class="thead-light">
						<tr>
							<th width="40">
								<input type="checkbox" id="select-all">
							</th>
							<th>{{ trans('general.name') }}</th>
							<th>{{ trans_choice('payment-schedules::general.bank', 1) }}</th>
							<th>{{ trans('payment-schedules::general.paybill') }}</th>
							<th>{{ trans('payment-schedules::general.account_number') }}</th>
							<th>{{ trans('payment-schedules::general.amount') }}</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($rows as $row)
							@php($ready = filled($row->paybill) && filled($row->account_number))
							<tr class="{{ $ready ? '' : 'table-warning' }}">
								<td>
									@if ($ready)
										<input type="checkbox" class="row-check" name="demarcations[]" value="{{ $row->demarcation_id }}" data-amount="{{ $row->total }}">
									@endif
								</td>
								<td>{{ $row->name }}</td>
								<td>{{ $row->bank_name ?: trans('general.na') }}</td>
								<td>{{ $row->paybill ?: trans('payment-schedules::general.missing_paybill') }}</td>
								<td>{{ $row->account_number ?: trans('general.na') }}</td>
								<td>@money($row->total, setting('default.currency'), true)</td>
							</tr>
						@empty
							<tr>
								<td colspan="6" class="text-center text-muted">{{ trans('payment-schedules::general.empty_items') }}</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</form>
@endsection

@push('scripts_start')
<script>
	$(function () {
		$('#select-all').on('change', function () {
			$('.row-check').prop('checked', $(this).is(':checked'));
		});
	});
</script>
@endpush
