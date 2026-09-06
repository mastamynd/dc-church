@extends('layouts.admin')

@section('title', $cycle->name)

@section('new_button')
	@if ($cycle->isOpen())
		@can('create-payment-schedules-schedules')
			<a href="{{ route('payment-schedules.schedules.create', [$cycle->id, 'region']) }}" class="btn btn-primary btn-sm">{{ trans('payment-schedules::general.region_batch') }}</a>
			<a href="{{ route('payment-schedules.schedules.create', [$cycle->id, 'sub_region']) }}" class="btn btn-success btn-sm">{{ trans('payment-schedules::general.sub_region_batch') }}</a>
		@endcan
		@can('update-payment-schedules-cycles')
			<form method="POST" action="{{ route('payment-schedules.cycles.close', $cycle->id) }}" class="d-inline">
				@csrf
				<button type="submit" class="btn btn-white btn-sm">{{ trans('payment-schedules::general.close_cycle') }}</button>
			</form>
		@endcan
	@endif
	@can('update-payment-schedules-cycles')
		<a href="{{ route('payment-schedules.cycles.edit', $cycle->id) }}" class="btn btn-white btn-sm">{{ trans('general.edit') }}</a>
	@endcan
@endsection

@section('content')
	<div class="row">
		<div class="col-md-4">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">{{ trans_choice('payment-schedules::general.billing_cycle', 1) }}</h3>
				</div>
				<div class="card-body">
					<p><strong>{{ trans('payment-schedules::general.scheduled_date') }}:</strong> {{ $cycle->scheduled_date ? $cycle->scheduled_date->format('Y-m-d') : '' }}</p>
					<p>
						<strong>{{ trans_choice('general.statuses', 1) }}:</strong>
						<span class="badge badge-{{ $cycle->statusBadge() }}">{{ trans('payment-schedules::general.status_labels.' . $cycle->status) }}</span>
					</p>
					@if ($cycle->notes)
						<p>{{ $cycle->notes }}</p>
					@endif
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">{{ trans_choice('payment-schedules::general.payment_schedule', 2) }}</h3>
				</div>
				<div class="table-responsive custom">
					<table class="table table-flush table-hover">
						<thead class="thead-light">
							<tr>
								<th>{{ trans_choice('general.types', 1) }}</th>
								<th>{{ trans('payment-schedules::general.scheduled_date') }}</th>
								<th>{{ trans('payment-schedules::general.total') }}</th>
								<th>{{ trans_choice('general.statuses', 1) }}</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($cycle->schedules as $schedule)
								<tr>
									<td>
										<a href="{{ route('payment-schedules.schedules.show', $schedule->id) }}">
											{{ $schedule->type === 'region' ? trans('payment-schedules::general.region_batch') : trans('payment-schedules::general.sub_region_batch') }}
										</a>
									</td>
									<td>{{ $schedule->scheduled_date ? $schedule->scheduled_date->format('Y-m-d') : '' }}</td>
									<td>@money($schedule->total_amount, setting('default.currency'), true)</td>
									<td>
										<span class="badge badge-{{ $schedule->statusBadge() }}">
											{{ trans('payment-schedules::general.status_labels.' . $schedule->status) }}
										</span>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="4" class="text-center text-muted">{{ trans('general.no_records') }}</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection
