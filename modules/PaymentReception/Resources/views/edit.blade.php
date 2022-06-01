@extends('layouts.admin')

@section('title', trans('payment-reception::general.title'))

@section('new_button')
	@can('update-payment-reception-main')
		<span class="btn btn-success btn-sm" data-target="#post-payment-modal" data-toggle="modal">
			{{ trans('payment-reception::general.post') }}
		</span>
	@endcan
@endsection

@section('content')
	{!! Form::open([
		'route' => ['payment-reception.store-tithe', isset($paymentReception) && isset($paymentReception->id) ? $paymentReception->id : 'create'],
		'id' => 'payment-reception',
		'@submit.prevent' => 'onSubmit',
		'@keydown' => 'form.errors.clear($event.target.name)',
		'files' => true,
		'role' => 'form',
		'autocomplete' => "off",
		'class' => 'form-loading-button needs-validation',
		'novalidate' => 'true'
	]) !!}
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">{{ trans_choice('church::general.title', 1) }}</label>
							<div class="input-group input-group-merge">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fa fa-user"></i>
									</span>
								</div>
								<select data-url="{{ route('church.get_church') }}/" name="church_id" placeholder="{{ trans('church::general.name') }}..." class="form-control selectize-field" data-searchfield="codename" data-labelfield="codename" data-valuefield="id">
									@foreach ($churches as $ch)
										<option value="{{ $ch->id }}" {{ isset($paymentReception) && $paymentReception->church_id == $ch->id ? 'selected' : '' }}>{{ $ch->codename }}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-sm-6">
						<div class="form-group">
							<label class="control-label">{{ trans_choice('payment-reception::general.payment_mode', 1) }}</label>
							<div class="input-group input-group-merge">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fa fa-user"></i>
									</span>
								</div>
								<select class="form-control selectize-field" name="payment_mode" placeholder="{{ trans_choice('payment-reception::general.payment_mode', 1) }}">
									@foreach ($payment_modes as $p=>$m)
										<option value="{{ $p }}" {{ isset($paymentReception) && $paymentReception->payment_mode == $p ? 'selected' : '' }}>{{ $m }}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-sm-6">
						<div class="form-group">
							<label class="control-label">{{ trans_choice('general.reference', 1) }}</label>
							<div class="input-group input-group-merge">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fa fa-user"></i>
									</span>
								</div>
								<input type="text" name="reference" value="{{ isset($paymentReception) && $paymentReception->reference }}" class="form-control" placeholder="Enter {{ trans_choice('general.reference', 1) }}..." />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<div class="py-2">
					<h4><i class="fas fa-user"></i> PAYMENT MADE BY</h4>
				</div>
				<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label">{{ trans_choice('general.name', 1) }}</label>
							<div class="input-group input-group-merge">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fas fa-font"></i>
									</span>
								</div>
								<input type="text" name="payer_name" value="{{ $paymentReception->payer_name ?? ( user()->name ?? '' ) }}" class="form-control" placeholder="Enter {{ trans_choice('general.name', 1) }}..." />
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label">{{ trans_choice('general.email', 1) }}</label>
							<div class="input-group input-group-merge">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fas fa-at"></i>
									</span>
								</div>
								<input type="email" name="payer_email" value="{{ $paymentReception->payer_email ?? ( user()->email ?? '' ) }}" class="form-control" placeholder="Enter {{ trans_choice('general.email', 1) }}..." />
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label">{{ trans_choice('general.phone', 1) }}</label>
							<div class="input-group input-group-merge">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fas fa-phone"></i>
									</span>
								</div>
								<input type="text" name="payer_phone" value="{{ $paymentReception->payer_phone ?? ( user()->phone ?? '' ) }}" class="form-control" placeholder="Enter {{ trans_choice('general.phone', 1) }}..." />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<div class="py-2 d-flex justify-content-around">
					<h4 class="w-100"><i class="fas fa-list"></i> PAYMENT ITEMS</h4>
					<button type="button" class="btn btn-sm btn-transparent text-primary" id="add-a-line">
						<i class="fas fa-plus"></i>
					</button>
				</div>
				<div class="table-responsive">
					<table class="table table-flush table-hover">
						<thead class="thead-light">
							<tr class="table-head-line">
								<th colspan="2">{{ trans('general.year') }}</th>
								<th colspan="2">{{ trans('general.month') }}</th>
								<th colspan="2">{{ trans('payment-reception::general.week') }}</th>
								<th colspan="2">{{ trans('general.amount') }}</th>
								<th colspan="2">{{ trans('payment-reception::general.top_up') }}</th>
								<th colspan="1"></th>
							</tr>
						</thead>
						<tbody id="payment-item-row" data-items="{{ json_encode($paymentReception->items ?? []) }}"></tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-footer">
				<div class="row save-buttons">
					{{ Form::saveButtons('church.index') }}
				</div>
			</div>
		</div>
	{!! Form::close() !!}
@endsection

@push('scripts_start')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css"
	integrity="sha512-bkB9w//jjNUnYbUpATZQCJu2khobZXvLP5GZ8jhltg7P/dghIrTaSJ7B/zdlBUT0W/LXGZ7FfCIqNvXjWKqCYA=="
	crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet"
	href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.bootstrap4.min.css"
	integrity="sha512-MMojOrCQrqLg4Iarid2YMYyZ7pzjPeXKRvhW9nZqLo6kPBBTuvNET9DBVWptAo/Q20Fy11EIHM5ig4WlIrJfQw=="
	crossorigin="anonymous" referrerpolicy="no-referrer" />
	<style type="text/css" rel="stylesheet">	
		.selectize-dropdown, .selectize-input { 
			line-height: 18px; 
			padding: 13px;
		}
	</style>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js"
	integrity="sha512-pF+DNRwavWMukUv/LyzDyDMn8U2uvqYQdJN0Zvilr6DDo/56xPDZdDoyPDYZRSL4aOKO/FGKXTpzDyQJ8je8Qw=="
	crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script>
		var addRow = function(data={}){
			var idd = data.id || Math.ceil(Math.random()*10000);

			var selectedYear = data.year || {{ $paymentYear }};
			var selectedMonth = data.month || '{{ $paymentYear }}';
			var selectedWeek = data.week || {{ $paymentWeek }};
			
			var $tr = $(`
				<tr>
					<td colspan="2">
						<div class="no-gutters">
							<div class="input-group input-group-merge">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fas fa-calendar"></i>
									</span>
								</div>
								<select class="form-control selectize-field" name="data[${idd}][year]" placeholder="{{ trans('general.year') }}">
									@for($y = $start_year; $y<=date('Y'); $y++)
										<option value="{{ $y }}" ${ {{ $y }} ==  selectedYear ? 'selected' : '' }>{{ $y }}</option>
									@endfor
								</select>
							</div>
						</div>
					</td>
					<td colspan="2">
						<div class="no-gutters no-gutters">
							<div class="input-group input-group-merge">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fas fa-calendar"></i>
									</span>
								</div>
								<select class="form-control selectize-field" name="data[${idd}][month]" placeholder="{{ trans('general.month') }}">
									@foreach($months['long'] as $id=>$mn)
										<option value="{{ strtolower($mn) }}" ${ '{{ strtolower($mn) }}' ==  selectedMonth.toLowerCase() ? 'selected' : '' } >{{ $mn }}</option>
									@endforeach
								</select>
							</div>
						</div>
					</td>
					<td colspan="2">
						<div class="no-gutters">
							<div class="input-group input-group-merge">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fas fa-calendar"></i>
									</span>
								</div>
								<select class="form-control selectize-field" name="data[${idd}][week]" placeholder="{{ trans_choice('payment-reception::general.week', 1) }}">
									@foreach($weeks as $wk)
										<option value="{{ $wk }}" ${ '{{ $wk }}' ==  selectedWeek ? 'selected' : '' } >{{ $wk }}</option>
									@endforeach
								</select>
							</div>
						</div>
					</td>
					<td colspan="2">
						<div class="no-gutters">
							<div class="input-group input-group-merge">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class=""></i>
									</span>
								</div>
								<input type="number" min="0" value="${data.amount || 0}" step="any" name="data[${idd}][amount]" class="form-control" placeholder="Enter {{ trans_choice('general.amount', 1) }}..." />
							</div>
						</div>
					</td>
					<td colspan="2">
						<div class="no-gutters">
							<div class="input-group input-group-merge">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class=""></i>
									</span>
								</div>
								<input type="number" min="0" value="${data.topup || 0}" step="any" name="data[${idd}][topup]" class="form-control" placeholder="Enter {{ trans_choice('payment-reception::general.topup', 1) }}..." />
							</div>
						</div>
					</td>
					<td colspan="1 pt-2">
						<span class="btn btn-sm text-danger btn-transparent remove-td">
							<i class="fas fa-trash"></i>
						</span>
					</td>
				</tr>
			`);

			var $trr = $tr.clone();

			$trr.on('click', '.remove-td', function(){
				$trr.remove();
			});

			$trr.find('select').each(function(){
				$(this).selectize();
			});

			return $trr; 
		}

		var selectizerNow = function(tis){
			var $url = $(tis).data('url');
			var $valueField = $(tis).data('valuefield');
			var $labelField = $(tis).data('labelfield');
			var $searchField = $(tis).data('searchfield');
			var $icon = $(tis).data('icon');
			var locked = $(this).attr('readonly') == 'true';

			if($url){
				$(tis).selectize({
					valueField: $valueField,
					labelField: $labelField,
					searchField: $searchField,
					create: false,
					render: {
						option: function (item, escape) {
							return (
								`<div class="px-2 py-3 border-bottom"><i class="far ${$icon}"></i> ${item[$labelField]}</div>`
							);
						},
					},
					load: function (query, callback) {
						if (!query.length) return callback();
						var $voucherType = $('select[name="payment_voucher_type"]').val();

						$.ajax({
							url: $url + encodeURIComponent(query),
							type: "GET",
							data: {
								'voucher_type': $voucherType
							},
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

			if(locked){
				tis[0].selectize.lock();
			}
		}

		$(function(){
			var items = $('#payment-item-row').data('items');
			$(this).find('.selectize-field').each(function() {
				if ($(this)[0].selectize) {
					$(this)[0].selectize.destroy();
				}
				selectizerNow(this);
			});

			$('#add-a-line').on('click', function(){
				var $row = addRow();
				$('#payment-item-row').append($row);
			});
			if(items.length == 0){
				$('#add-a-line').trigger('click');
			}
			else{
				$.each(items, function(i, it){
					var $row = addRow(it);
					$('#payment-item-row').append($row);
				});
			}
		});
	</script>
	<div class="modal fade show" id="post-payment-modal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="{{ route('payment-reception.post-payment', $paymentReception->id ?? 'create') }}"  method="post">
					@csrf
					<div class="modal-header border-bottom" style="background-color: #f8f9fe;">
						{{ trans('payment-reception::general.approve_tithe') }}
					</div>
					<div class="modal-body">
						<div class="alert alert-info">
							<i class="fas fa-info-circle float-left fa-2x mr-2 mb-2"></i> Are you sure that you want to approve this tithe?
						</div>
					</div>
					<div class="modal-footer border-top" style="background-color: #f8f9fe;">
						<span class="btn btn-white" data-dismiss="modal">Close</span>
						<button type="submit" class="btn btn-success">YES, APPROVE</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	{{-- <script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script> --}}
@endpush