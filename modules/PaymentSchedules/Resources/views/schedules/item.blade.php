@extends('layouts.admin')

@section('title', ($item->demarcation->name ?? trans_choice('payment-schedules::general.items', 1)))

@section('new_button')
	<a href="{{ route('payment-schedules.schedules.show', $schedule->id) }}" class="btn btn-white btn-sm">{{ trans('payment-schedules::general.back') }}</a>
@endsection

@section('content')
	<div class="card">
		<div class="card-header">
			<h3 class="card-title">
				{{ $item->demarcation->name ?? trans('general.na') }}
				<small>{{ $item->paybill }} / {{ $item->account_number }} — @money($item->amount, setting('default.currency'), true)</small>
			</h3>
		</div>
		<div class="table-responsive custom">
			<table class="table table-flush">
				<thead class="thead-light">
					<tr>
						<th>{{ trans_choice('demarcations::general.church', 1) }}</th>
						<th>{{ trans('general.month') }}</th>
						<th>{{ trans('general.year') }}</th>
						<th>{{ trans('payment-schedules::general.amount') }}</th>
					</tr>
				</thead>
				<tbody>
					@forelse ($lines as $line)
						<tr>
							<td>{{ $line->church_name }}</td>
							<td>{{ $line->month }}</td>
							<td>{{ $line->year }}</td>
							<td>@money($line->total, setting('default.currency'), true)</td>
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
@endsection
