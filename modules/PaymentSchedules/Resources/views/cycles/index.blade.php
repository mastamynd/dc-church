@extends('layouts.admin')

@section('title', trans_choice('payment-schedules::general.billing_cycle', 2))

@section('new_button')
	@can('create-payment-schedules-cycles')
		<a href="{{ route('payment-schedules.cycles.create') }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
	@endcan
	@can('read-payment-schedules-approvals')
		<a href="{{ route('payment-schedules.approvals.edit') }}" class="btn btn-white btn-sm">{{ trans('payment-schedules::general.approval_configuration') }}</a>
	@endcan
@endsection

@section('content')
	@if ($cycles->count() || request()->get('search', false))
		<div class="card">
			<div class="card-header border-bottom-0">
				{!! Form::open([
					'method' => 'GET',
					'route' => 'payment-schedules.cycles.index',
					'role' => 'form',
					'class' => 'mb-0'
				]) !!}
					<x-search-string model="Modules\PaymentSchedules\Models\BillingCycle" />
				{!! Form::close() !!}
			</div>
			<div class="table-responsive custom">
				<table class="table table-flush table-hover">
					<thead class="thead-light">
						<tr class="table-head-line">
							<th>@sortablelink('name', trans('general.name'))</th>
							<th>@sortablelink('scheduled_date', trans('payment-schedules::general.scheduled_date'))</th>
							<th>@sortablelink('status', trans_choice('general.statuses', 1))</th>
							<th>{{ trans_choice('payment-schedules::general.payment_schedule', 2) }}</th>
							<th>{{ trans('general.actions') }}</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($cycles as $item)
							<tr class="align-items-center border-top-1">
								<td>
									<a href="{{ route('payment-schedules.cycles.show', $item->id) }}">{{ $item->name }}</a>
								</td>
								<td>{{ $item->scheduled_date ? $item->scheduled_date->format('Y-m-d') : '' }}</td>
								<td>
									<span class="badge badge-{{ $item->statusBadge() }}">
										{{ trans('payment-schedules::general.status_labels.' . $item->status) }}
									</span>
								</td>
								<td>{{ $item->schedules_count }}</td>
								<td class="text-center">
									<div class="dropdown">
										<a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<i class="fa fa-ellipsis-h text-muted"></i>
										</a>
										<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
											<a class="dropdown-item" href="{{ route('payment-schedules.cycles.show', $item->id) }}">
												{{ trans('general.show') }}
											</a>
											@can('update-payment-schedules-cycles')
												<a class="dropdown-item" href="{{ route('payment-schedules.cycles.edit', $item->id) }}">
													{{ trans('general.edit') }}
												</a>
											@endcan
											@can('delete-payment-schedules-cycles')
												{!! Form::deleteLink($item, 'payment-schedules.cycles.destroy', 'payment-schedules::general.billing_cycle') !!}
											@endcan
										</div>
									</div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<div class="card-footer table-action">
				<div class="row">
					@include('partials.admin.pagination', ['items' => $cycles])
				</div>
			</div>
		</div>
	@else
		<div class="card">
			<div class="card-body text-center p-5">
				<p class="mb-4">{{ trans('payment-schedules::general.empty_cycles') }}</p>
				@can('create-payment-schedules-cycles')
					<a href="{{ route('payment-schedules.cycles.create') }}" class="btn btn-success">
						{{ trans('general.title.create', ['type' => trans_choice('payment-schedules::general.billing_cycle', 1)]) }}
					</a>
				@endcan
			</div>
		</div>
	@endif
@endsection
