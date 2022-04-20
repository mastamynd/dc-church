@extends('layouts.admin')

@section('title', trans('gl-posting-settings::general.reconsiliation'))

@section('new_button')	
{{-- <button class="btn btn-success btn-sm" id="reconsiliation-btn" disabled data-target="#reconsiliation-modal" data-toggle="modal">
	{{ trans('gl-posting-settings::general.reconcile') }}
</button> --}}
	<a href="{{ route('gl-posting-settings.get-posting-details', 'new') }}" class="btn btn-white btn-sm">
		{{ trans('gl-posting-settings::general.add_new') }}
	</a>
@endsection

@section('content')
	<div id="app">
		<div class="card">
			<div class="card-header border-bottom-0" :class="[{'bg-gradient-info': bulk_action.show}]">
				{!! Form::open([
					'method' => 'GET',
					'route' => 'gl-posting-settings.recon-index',
					'role' => 'form',
					'class' => 'mb-0'
				]) !!}
					<div class="align-items-center" v-if="!bulk_action.show">
						<div class="position-relative js-search-box-hidden row" style="height: 48.5px;">
							<div class="border-bottom-0 col-sm-3" style="z-index: 9;">
								<input type="text" value="{{ $searchTerm }}" name="search" placeholder="Search or filter results..." class="form-control" />
							</div>
							<div class="col-sm-3">
								<input class="form-control" type="datetime-local" name="start" value="{{ $start }}" placeholder="Start Date..." />
							</div>
							<div class="col-sm-3">
								<input class="form-control" max="{{ \Carbon\Carbon::now()->addDays(1) }}" type="datetime-local" name="end" value="{{ $end }}" placeholder="End Date..." />
							</div>
							<div class="col-sm-2">
								@php($statuses = ['reconciled', 'Pending'])
								<select class="form-control selectize-field" name="other_status" value="{{ $end }}" placeholder="Filter By Status...">
									<option value="">Filter By Status</option>
									@foreach ($statuses as $st)
										<option value="{{ $st }}" {{ $otherStatus == $st ? 'selected' : '' }}>{{ ucwords($st) }}</option>
									@endforeach
								</select>
							</div>
							<div class="col-sm-1">
								<button class="btn btn-success btn-lg btn-block"><i class="fas fa-arrow-right"></i></button>
							</div>
						</div>
					</div>
					{{ Form::bulkActionRowGroup('gl-posting-settings::general.reconsiliation', $bulk_actions ?? [], ['group' => 'gl-posting-settings', 'type' => 'bank_integration_payloads']) }}
				{!! Form::close() !!}
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-flush table-hover ellipsized">
						<thead class="thead-light">
							<tr class="row table-head-line">
								<th class="d-none d-sm-block col-sm-1" id="toggle-checks-th">{{ Form::bulkActionAllGroup() }}</th>
								<th class="col-xs-4 col-sm-4 col-xl-3 col-md-3" data-r_name="{{ trans('gl-posting-settings::general.r_name') }}">@sortablelink('payload', trans('gl-posting-settings::general.r_name'))</th>
								<th class="col-xs-3 col-sm-2 col-xl-1 col-md-2" data-r_admin_no="{{ trans('gl-posting-settings::general.narrative') }}">@sortablelink('payload', trans('gl-posting-settings::general.narrative'))</th>
								<th class="d-none d-md-block col-xl-1 col-md-2" data-r_transaction_date="{{ trans('gl-posting-settings::general.r_transaction_date') }}">@sortablelink('transaction_time', trans('gl-posting-settings::general.r_transaction_date'))</th>
								<th class="d-none d-xl-block col-xl-1" data-r_bank="{{ trans('gl-posting-settings::general.r_bank') }}">@sortablelink('bank', trans('gl-posting-settings::general.r_bank'))</th>
								<th class="d-none d-xl-block col-xl-1" data-r_account="{{ trans('gl-posting-settings::general.r_account') }}">@sortablelink('payload', trans('gl-posting-settings::general.r_account'))</th>
								<th class="col-xs-3 col-sm-3 col-xl-1 col-md-2" data-r_amount="{{ trans('gl-posting-settings::general.r_amount') }}">@sortablelink('payload', trans('gl-posting-settings::general.r_amount'))</th>
								<th class="d-none d-xl-block col-xl-1" data-r_status="{{ trans('gl-posting-settings::general.r_status') }}">@sortablelink('status', trans('gl-posting-settings::general.r_status'))</th>
								<th class="d-none d-xl-block col-xl-1" data-r_recon_status="{{ trans('gl-posting-settings::general.r_recon_status') }}">@sortablelink('payload', trans('gl-posting-settings::general.r_recon_status'))</th>
								<th class="col-xs-2 col-sm-2 col-xl-1 col-md-2" data-r_details="{{ trans('gl-posting-settings::general.r_details') }}">@sortablelink('payload', trans('gl-posting-settings::general.r_details'))</th>
							</tr>
						</thead>
						<tbody id="checked-transactions-list">
							@foreach ($recons as $app)
								@php($payload = json_decode($app->payload))
								<?php
									if(isset($payload->User)){
										unset($payload->User);
										unset($payload->Password);
										unset($payload->HashVal);
									}
								?>
								@if($payload)
									<tr class="row align-items-center border-top-1" data-id="{{ $app->id }}" data-data="{{ json_encode($payload) }}">
										<td class="d-none d-sm-block col-sm-1 is-checked-row">{{ Form::bulkActionGroup($app->id, $app->pay_load_hash) }}</td>
										<td class="col-xs-4 col-sm-4 col-xl-3 col-md-3">
											<a href="{{ route('gl-posting-settings.get-posting-details', $app->id) }}" 
												{!! ($app->status) == 'DUPLICATE' ? 'disabled="disabled"' : '' !!}>
												{{ $app->customer->name }}
											</a>
										</td>
										<td class="col-xs-3 col-sm-2 col-xl-1 col-md-2">{{ $app->customer->reference ?? '-' }}</td>
										<td class="d-none d-md-block col-xl-1 col-md-2">{{ $app->transaction_time }}</td>
										<td class="d-none d-xl-block col-xl-1">{{ $app->bank }}</td>
										<td class="d-none d-xl-block col-xl-1">{{ $app->account->name ?? 'MISSING' }}</td>
										<td class="col-xs-3 col-sm-4 col-xl-1 col-md-2">{{ number_format($payload->TransAmount ?? 0,2) }}</td>
										<td class="d-none d-xl-block col-xl-1">{{ $payload->Status ?? ($payload->status ?? 'Missing') }}</td>
										<td class="d-none d-xl-block col-xl-1">{{ $app->status }}</td>
										<td class="col-xs-2 col-sm-2 col-xl-1 col-md-2 escapee">
											<span class="btn btn-sm btn-transparent text-info" data-payload="{{ json_encode($payload) }}" data-target="#transaction-details-modal" data-toggle="modal">
												<i class="fas fa-info-circle"></i>
											</span>
										</td>
									</tr>
								@endif
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
			<div class="card-footer table-action">
				<div class="row align-items-center">
					@php($limits = json_decode('{"10":"10","25":"25","50":"50","100":"100"}'))
					@include('partials.admin.pagination', ['items' => $recons, 'limits'=>$limits])
				</div>
			</div>
		</div>
	</div>
@stop
@push('scripts_start')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css" integrity="sha512-bkB9w//jjNUnYbUpATZQCJu2khobZXvLP5GZ8jhltg7P/dghIrTaSJ7B/zdlBUT0W/LXGZ7FfCIqNvXjWKqCYA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.bootstrap4.min.css" integrity="sha512-MMojOrCQrqLg4Iarid2YMYyZ7pzjPeXKRvhW9nZqLo6kPBBTuvNET9DBVWptAo/Q20Fy11EIHM5ig4WlIrJfQw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js" integrity="sha512-pF+DNRwavWMukUv/LyzDyDMn8U2uvqYQdJN0Zvilr6DDo/56xPDZdDoyPDYZRSL4aOKO/FGKXTpzDyQJ8je8Qw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>	
	<script>
		var checkedItems = [];
		var onChangePaginationLimit = function(e) {
			var t = "";
			if (window.location.search.length)
				if (window.location.search.includes("limit")) {
					var n = [],
						i = window.location.search;
					n = (i = i.replace("?", "")).split("&"), t = window.location.origin + window.location.pathname, n.forEach((function(n, i) {
						var r = n.split("=");
						t += 0 == i ? "?" : "&", "limit" == r[0] ? t += "limit=" + e.target.value : t += r[0] + "=" + r[1]
					}))
				} else t = window.location.href + "&limit=" + e.target.value;
			else t = window.location.href + "?limit=" + e.target.value;
			window.location.href = t;
		}

		var checkChecked = function(){
			checkedItems = [];

			$('#checked-transactions-list').find('.is-checked-row').find('input[type="checkbox"]').each(function(){
				var isChecked = $(this).is(':checked');
				if(isChecked){
					var thisRow = $(this).parents('tr');
					var data = thisRow.data('data');

					data.id = thisRow.data('id');

					checkedItems.push(data);
				}
			});

			if(checkedItems.length > 0){
				$('#reconsiliation-btn').attr('disabled', false);
				$('#reconsiliation-btn').prop('disabled', false);
				$('#reconsiliation-btn').removeAttr('disabled');
				$('#reconsiliation-btn').removeProp('disabled');
			}
			else{
				$('#reconsiliation-btn').attr('disabled', true);
				$('#reconsiliation-btn').prop('disabled', true);
			}
		}

		$(function(){
			var selectizerNow = function(tis){
				var $url = $(tis).data('url');
				
				if($url){
					$(tis).selectize({
						valueField: "code",
						labelField: "acc_code",
						searchField: "account",
						create: false,
						render: {
							option: function (item, escape) {
								return (
									`<div class="p-2"><i class="far fa-address-card"></i> ${item.account} <small class="text-muted">${item.code}</small></div>`
								);
							},
						},
						load: function (query, callback) {
							if (!query.length) return callback();
							$.ajax({
								url: $url + encodeURIComponent(query),
								type: "GET",
								error: function () {
									callback();
								},
								success: function (res) {
									callback(res);
								},
							});
						},
					});
				}
				else{
					$(tis).selectize();
				}
			}

			$('#reconsiliation-modal').on('shown.bs.modal', function (e) {
				$('#records-list').empty();
				$.each(checkedItems, function(c,t){

					$('#records-list').append(`<div class="d-flex col-md-6 justify-items-center align-items-center pr-2 mb-2">
						<div class="custom-control custom-checkbox">
							<input type="checkbox" class="custom-control-input" id='checked-transaction-${t.id}' checked="checked">
							<label class="custom-control-label" for="checked-transaction-${t.id}"></label>
						</div>
						<div class="d-flex flex-column">
							<div class="d-flex">
								<div class="p-1"><small>${$.trim(t.narrative || t.Narrative)}</small></div>
								<div class="p-1"><small>${t.customer_name}</small></div>
								<div class="p-1"><small>${numberWithCommas(t.TransAmount || t.amount)}</small></div>
							</div>
							<small class="text-muted"><small>${t.CustomerName || t.narrative}</small></small>
						</div>	
					</div>`
					);
				});
			});

			$('#transaction-details-modal').on('shown.bs.modal', function (e) {
				var target = $(e.relatedTarget);
				var data = target.data('payload');
				var bd = $(this).find('.modal-body');
				var $table = $(`<table class="table"></table>`);
				if(data){
					$.each(data, function(d,t){
						var $tr = $(`<tr><th>${d}</th><td class="break-words">${t}</td></tr>`);

						$table.append($tr);
					});
				}
				else{
					$table.html(`<tr>
						<td>
							<div class="alert alert-warning">
								<i class="fas fa-exclamation-triangle"></i> Missing Transaction Details! Contact Bank
							</div>
						</td>
					</tr>`);
				}
				

				bd.html($table);
			});

			$('#toggle-checks-th').on('change', 'input[type="checkbox"]', function(){
				var isChecked = $(this).is(':checked');
				var tableTH = $(this).parents('table').find('tbody');
				tableTH.find('.is-checked-row').find('input[type="checkbox"]').each(function(){
					if(isChecked){
						$(this).prop('checked', true);
						$(this).attr('checked', true);
					}
					else{
						$(this).prop('checked', false);
						$(this).attr('checked', false);

						$(this).removeProp('checked');
						$(this).removeAttr('checked');
					}
				});
				checkChecked();
			});
			
			$('#checked-transactions-list').find('.is-checked-row').find('input[type="checkbox"]').on('change', function(){
				checkChecked();
			});

			$(this).find('.selectize-field').each(function() {
				if ($(this)[0].selectize) {
					$(this)[0].selectize.destroy();
				}
				selectizerNow(this);
			});
		});
	</script>
	<div class="modal" tabindex="-1" id="transaction-details-modal" style="z-index: 999999 !important;">
		<div class="modal-dialog">
			<div class="modal-content">
				<input type="hidden" name="_token" value="TKadspicEuKKt5d5AlPbmqiD2ibxHba6VJqf9bks">				<div class="modal-header">
					<h5 class="modal-title">Payload Details</h5>
					<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body"></div>
			</div>
		</div>
	</div>
	<div class="modal" tabindex="-1" id="reconsiliation-modal" style="z-index: 999999 !important;">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title">Reconsiliation Details</h5>
					<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<h5>Transactions</h5>
					<div id="records-list" class="row no-gutters"></div>
				</div>
			</div>
		</div>
	</div>
@endpush