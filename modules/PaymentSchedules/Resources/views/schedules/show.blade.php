@extends('layouts.admin')

@section('title', trans_choice('payment-schedules::general.payment_schedule', 1) . ' #' . $schedule->id)

@section('new_button')
	<a href="{{ route('payment-schedules.cycles.show', $schedule->billing_cycle_id) }}" class="btn btn-white btn-sm">{{ trans('payment-schedules::general.back') }}</a>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header d-flex justify-content-between">
					<h3 class="card-title mb-0">
						{{ $schedule->type === 'region' ? trans('payment-schedules::general.region_batch') : trans('payment-schedules::general.sub_region_batch') }}
						<span class="badge badge-{{ $schedule->statusBadge() }} ml-2">
							{{ trans('payment-schedules::general.status_labels.' . $schedule->status) }}
						</span>
					</h3>
					<span class="badge badge-dark badge-pill">@money($schedule->total_amount, setting('default.currency'), true)</span>
				</div>
				<div class="table-responsive custom">
					<table class="table table-flush">
						<thead class="thead-light">
							<tr>
								<th>{{ trans('payment-schedules::general.destination') }}</th>
								<th>{{ trans('payment-schedules::general.paybill') }}</th>
								<th>{{ trans('payment-schedules::general.account_number') }}</th>
								<th>{{ trans('payment-schedules::general.amount') }}</th>
								<th>{{ trans_choice('general.statuses', 1) }}</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($schedule->items as $item)
								<tr>
									<td>
										<a href="{{ route('payment-schedules.schedules.item', [$schedule->id, $item->id]) }}">
											{{ $item->demarcation->name ?? trans('general.na') }}
										</a>
									</td>
									<td>{{ $item->paybill }}</td>
									<td>{{ $item->account_number }}</td>
									<td>@money($item->amount, setting('default.currency'), true)</td>
									<td>
										<span class="badge badge-{{ $item->statusBadge() }}">
											{{ trans('payment-schedules::general.status_labels.' . $item->status) }}
										</span>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>

			<div class="card">
				<div class="card-header">
					<h3 class="card-title">{{ trans('payment-schedules::general.mpesa_details') }}</h3>
				</div>
				<div class="table-responsive custom">
					<table class="table table-flush table-sm">
						<thead class="thead-light">
							<tr>
								<th>{{ trans('payment-schedules::general.destination') }}</th>
								<th>{{ trans('payment-schedules::general.conversation_id') }}</th>
								<th>{{ trans('payment-schedules::general.transaction_id') }}</th>
								<th>{{ trans_choice('general.statuses', 1) }}</th>
								<th>{{ trans('payment-schedules::general.result') }}</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($schedule->transactions as $txn)
								<tr>
									<td>{{ $txn->party_b }} / {{ $txn->account_reference }}</td>
									<td>{{ $txn->originator_conversation_id }}</td>
									<td>{{ $txn->transaction_id ?: trans('general.na') }}</td>
									<td>
										<span class="badge badge-{{ $txn->statusBadge() }}">
											{{ trans('payment-schedules::general.status_labels.' . $txn->status) }}
										</span>
									</td>
									<td>{{ $txn->result_desc }}</td>
								</tr>
							@empty
								<tr>
									<td colspan="5" class="text-muted text-center">{{ trans('general.no_records') }}</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">{{ trans_choice('payment-schedules::general.approval_step', 2) }}</h3>
				</div>
				<div class="card-body">
					@forelse ($schedule->approvals as $approval)
						<div class="border-bottom pb-2 mb-2">
							<div class="d-flex justify-content-between">
								<strong>{{ $approval->level }}. {{ $approval->name }}</strong>
								<span class="badge badge-{{ $approval->statusBadge() }}">
									{{ trans('payment-schedules::general.status_labels.' . $approval->status) }}
								</span>
							</div>
							<div class="text-sm text-muted">
								{{ trans('payment-schedules::general.approver') }}: {{ $approval->approverName() }}
								@if ($approval->is_final)
									· {{ trans('payment-schedules::general.is_final') }}
								@endif
							</div>
							@if ($approval->actor)
								<div class="text-sm">{{ $approval->actor->name }} · {{ $approval->approved_at }}</div>
							@endif
							@if ($approval->comment)
								<div class="text-sm">{{ $approval->comment }}</div>
							@endif
						</div>
					@empty
						<p class="text-muted mb-0">{{ trans('payment-schedules::general.submit_for_approval') }}</p>
					@endforelse

					@if ($schedule->isDraft())
						<form method="POST" action="{{ route('payment-schedules.schedules.submit', $schedule->id) }}" class="mt-3">
							@csrf
							<button class="btn btn-primary btn-block">{{ trans('payment-schedules::general.submit_for_approval') }}</button>
						</form>
					@endif

					@if ($canApprove)
						<form method="POST" action="{{ route('payment-schedules.schedules.approve', $schedule->id) }}" class="mt-3">
							@csrf
							<div class="form-group">
								<label>{{ trans('payment-schedules::general.comment') }}</label>
								<textarea name="comment" class="form-control form-control-sm" rows="2"></textarea>
							</div>
							<button class="btn btn-success btn-block">{{ trans('payment-schedules::general.approve') }}</button>
						</form>
						<form method="POST" action="{{ route('payment-schedules.schedules.reject', $schedule->id) }}" class="mt-2">
							@csrf
							<button class="btn btn-danger btn-block">{{ trans('payment-schedules::general.reject') }}</button>
						</form>
					@endif

					@if ($schedule->status === 'otp_sent' || $schedule->status === 'approved')
						<div class="mt-3 p-2 border rounded">
							<p class="text-sm">{{ trans('payment-schedules::general.final_step_hint') }}</p>
							@if ($canEnterOtp || $schedule->status === 'approved')
								<form method="POST" action="{{ route('payment-schedules.schedules.otp.send', $schedule->id) }}" class="mb-2">
									@csrf
									<button class="btn btn-white btn-sm btn-block">
										{{ $schedule->status === 'otp_sent' ? trans('payment-schedules::general.resend_otp') : trans('payment-schedules::general.send_otp') }}
									</button>
								</form>
							@endif
							@if ($canEnterOtp)
								<form method="POST" action="{{ route('payment-schedules.schedules.otp.verify', $schedule->id) }}">
									@csrf
									<div class="form-group">
										<label>{{ trans('payment-schedules::general.otp_code') }}</label>
										<input type="text" name="code" class="form-control" maxlength="6" required>
									</div>
									<button class="btn btn-primary btn-block">{{ trans('payment-schedules::general.verify_otp') }}</button>
								</form>
							@endif
						</div>
					@endif

					@if ($schedule->isCancellable())
						<form method="POST" action="{{ route('payment-schedules.schedules.cancel', $schedule->id) }}" class="mt-3" onsubmit="return confirm('{{ trans('payment-schedules::general.confirm_cancel') }}');">
							@csrf
							<button class="btn btn-outline-danger btn-block">{{ trans('payment-schedules::general.cancel_schedule') }}</button>
						</form>
					@endif
				</div>
			</div>
		</div>
	</div>
@endsection
