@extends('layouts.admin')

@section('title', trans_choice('payment-reception::general.tithes_received', 1))

@section('new_button')
	@can('create-payment-reception-main')
		<a href="{{ route('payment-reception.receive-tithe') }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
		<span class="btn btn-info btn-sm" data-target="#trigger-payment-modal" data-toggle="modal">
			POST PAYMENTS
		</span>
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
							<th>@sortablelink('payer_name', 'Month')</th>
							<th>@sortablelink('amount', trans('general.amount'))</th>
							<th>@sortablelink('created_at', trans('general.date'))</th>
							<th>@sortablelink('status', 'Status')</th>
							<th>@sortablelink('status', 'Action')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($payments as $item)
							<tr class="align-items-center border-top-1 hoverable-row"
								data-id="{{ $item->id }}"
								data-amount="{{ (float) $item->amount }}"
								data-church-name="{{ $item->church->name ?? '' }}"
								data-church-code="{{ $item->church->code ?? '' }}">
								<td>
									{{ Form::bulkActionGroup($item->id, $item->name) }}
								</td>
								<td><a href="{{ route('payment-reception.view-tithe', $item->id) }}">{{ $item->church->name ?? '-' }}</a></td>
								<td><a href="{{ route('payment-reception.view-tithe', $item->id) }}">{{ $item->church->code ?? '-' }}</a></td>
								<td>{{ $item->payment_mode }}</td>
								<td>{{ ucfirst($item->month) }} <small>(Week-{{ $item->week }})</small></td>
								<td>@money($item->amount, setting('default.currency'), true)</td>
								<td>{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d H:i:s') }}</td>
								<td>{{ $item->status }}</td>
								<td class="text-center">
									<div class="dropdown">
										<a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<i class="fa fa-ellipsis-h text-muted"></i>
										</a>
										<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

											@can('update-demarcations-main')
												<a class="dropdown-item" href="{{ route('payment-reception.view-tithe', $item->id) }}">
													{{ trans('general.edit') }}
												</a>
											@endcan
											
											@can('delete-demarcations-main')
												<span class="dropdown-item" data-target="#delete-tithe-modal" data-toggle="modal" data-href="{{ route('payment-reception.delete-tithe', $item->id) }}">{{ trans('general.delete') }}</span>
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
					@include('partials.admin.pagination', ['items' => $payments])
				</div>
			</div>
		</div>
	@else
		<x-empty-page group="banking" page="transfers" textPage="{{ trans('payment-reception::general.tithe') }}" routeCreate="{{ 'payment-reception.receive-tithe' }}" textEmptyPage="{{ trans('payment-reception::general.tithes_received_msg') }}" />
	@endif
@endsection

@push('scripts_start')
	<div class="modal" tabindex="-1" id="trigger-payment-modal" style="z-index: 999999 !important;">
		<div class="modal-dialog">
			<form class="modal-content" method="POST" action="{{ route('payment-reception.post-payments') }}">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title">POST PAYMENTS</h5>
					<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="alert alert-warning">
						<i class="fas fa-exclamation-triangle fa-2x pull-left m-2"></i> {{ trans('gl-posting-settings::general.post_payment_modal_message') }}
					</div>
					<div class="row">
						<div class="form-group col-md-6">
							<div class="ids-for-posting"></div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<span data-dismiss="modal" class="btn btn-outline-secondary">Cancel</span>
					<button type="submit" class="btn btn-icon btn-success" id="trigger-posting-details-form-submit">{{ trans('gl-posting-settings::general.change_customer_modal_submit_btn') }}</button>
				</div>
			</form>
		</div>
	</div>
	<script>
		$(function(){
			$('#trigger-payment-modal').on('shown.bs.modal', function (e){
				var postingFor = $(this).find('.ids-for-posting');
				postingFor.empty();
				var strReceipt = $(`<div class="row"></div>`);
				$('.hoverable-row').find('.custom-control-input:checked').each(function(){
					var $row = $(this).closest('.hoverable-row');
					var amount = Number($row.attr('data-amount') || 0);
					var churchName = $row.attr('data-church-name') || '';
					var churchCode = $row.attr('data-church-code') || '';
					var val = $row.attr('data-id') || $(this).val();

					strReceipt.append(`<div class="m-1 p-2 float-right" style="font-size: 12px"><input type="checkbox" name="ids[]" checked value="${val}" /> ${ $('<div>').text(churchName + ' ' + churchCode).html() } - Ksh${amount.toFixed(2)}</div>`)
				});

				postingFor.append(strReceipt);
			});
			$("#delete-tithe-modal").on("shown.bs.modal", function (e){ 
				var urll = $(e.relatedTarget).data('href');

				$(this).find('form').attr('action', urll);
				$(this).find('form').prop('action', urll);
			});

			$('[type="submit"]').on('click', function(){
				$(this).hide();
			});	
		});
		</script>
		<div class="modal fade show" id="delete-tithe-modal"  role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<form method="post">
						@csrf
						<div class="modal-header border-bottom" style="background-color: #f8f9fe;">
							<i class="fas fa-trash"></i> Delete
						</div>
						<div class="modal-body">
							<div class="form-group form-group-lg">
								<div class="alert alert-danger">
									<i class="fas fa-exclamation-triangle fa-2x pull-left float-left"></i> Are you sure that you want to delete this?
								</div>
							</div>
						</div>
						<div class="modal-footer border-top" style="background-color: #f8f9fe;">
							<span class="btn btn-white" data-dismiss="modal">Close</span>
							<button type="submit" class="btn btn-danger">DELETE</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	<script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script>
@endpush
