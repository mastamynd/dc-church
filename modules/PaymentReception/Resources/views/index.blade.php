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

			<div class="table-responsive custom">
				<table class="table table-flush table-hover">
					<thead class="thead-light">
						<tr class="table-head-line">
							<th colspan="1%">{{ Form::bulkActionAllGroup() }}</th>
							<th>@sortablelink('church.name', trans('payment-reception::general.church'))</th>
							<th>@sortablelink('church.code', trans('church::general.code'))</th>
							<th>@sortablelink('payment_mode', trans('payment-reception::general.payment_mode'))</th>
							<th>@sortablelink('payer_name', trans('payment-reception::general.payment_by'))</th>
							<th>@sortablelink('amount', trans('general.amount'))</th>
							<th>@sortablelink('created_at', trans('general.date'))</th>
							<th>@sortablelink('status', trans('general.status'))</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($payments as $item)
							<tr class="align-items-center border-top-1">
								<td>
									{{ Form::bulkActionGroup($item->id, $item->name) }}
								</td>
								<td><a href="{{ route('payment-reception.view-tithe', $item->id) }}">{{ $item->church->name }}</a></td>
								<td><a href="{{ route('payment-reception.view-tithe', $item->id) }}">{{ $item->church->code }}</a></td>
								<td>{{ $item->payment_mode }}</td>
								<td>{{ $item->payer_name }}</td>
								<td>@money($item->total, 'KES')</td>
								<td>{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d H:i:s') }}</td>
								<td>{{ $item->status }}</td>
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
		<x-empty-page group="banking" page="transfers" textPage="{{ trans('payment-reception::general.tithe') }}" routeCreate="{{ 'payment-reception.receive-tithe' }}" textEmptyPage="{{ trans('payment-reception::general.tithes_received_msg') }}" />
	@endif
@endsection

@push('scripts_start')
	<script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script>
@endpush
