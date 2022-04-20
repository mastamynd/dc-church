@extends('layouts.admin')

@section('title', trans('gl-posting-settings::general.reconsiliation_breakdown'))

@section('new_button')
	@if(isset($recon->id) && $recon->status == 'reconciled')
		<a class="btn btn-secondary btn-sm" target="_blank" href="{{ route('gl-posting-settings.view-receipt', $recon->receipt->id ?? '') }}">{{ trans('gl-posting-settings::general.print_receipt') }}</a>
	@else
		<span class="btn btn-success btn-sm" data-target="#trigger-payment-modal" data-toggle="modal">{{ trans('gl-posting-settings::general.post_payment') }}</span>
	@endif
@endsection

@section('content')
	{!! Form::open([
		'id' => 'posting-details-form',
		'route' => ['gl-posting-settings.update-posting-details', $recon->id ?? 'new'],
		'files' => true,
		'role' => 'form',
		'method' => (isset($recon->id) && $recon->status == 'reconciled') ? 'GET' : 'POST',
		'class' => 'form-loading-button',
	]) !!}

	<div class="exceedAmnt-MSG">
		<div class="alert alert-danger">
			<i class="fas fa-exclamation-triangle"></i> The item total exceeds the amount paid!
		</div>
	</div>

	<div class="card">
		<div class="card-header with-border">
			<h3 class="card-title">{{ trans('gl-posting-settings::general.posting_details') }}</h3>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-4 col-sm-6">
					<div class="form-group">
						<label for="name" class="form-control-label">{{ trans('gl-posting-settings::general.customer_name') }}</label>
						<div class="input-group input-group-merge bg-grey">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fa fa-font"></i>
								</span>
							</div>
							<select data-url="{{ route('payment-plan.get-customers-json') }}/" required
								name="customer_id" data-valuefield="id" data-searchfield="name" data-labelfield="reference_str" placeholder="{{ trans('payment-plan::general.customer_numbers') }}..." 
								class="form-control selectize-field form-control-lg can-trigger-customer-change">
								<option value="">Select Customer...</option>
								@if(isset($CONTACT['id']))
									<option value="{{ $CONTACT['id'] }}" selected>{{ $CONTACT['name'] }}</option>
								@endif
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-6">
					<div class="form-group">
						<label for="name" class="form-control-label">{{ trans('gl-posting-settings::general.r_account') }}</label>
						<div class="input-group input-group-merge">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fa fa-money-check"></i>
								</span>
							</div>
							<input style="padding-left: .8rem" {{ $isNewPayment ? '' : 'readonly' }} placeholder="{{ trans('gl-posting-settings::general.r_account') }}" name="r_account" value="{{ $CONTACT['reference'] ?? '' }}" type="text" class="form-control">
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-6">
					<div class="form-group">
						<label for="name" class="form-control-label">{{ trans('gl-posting-settings::general.customer_classification') }}</label>
						<div class="input-group input-group-merge">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fas fa-user-graduate"></i>								
								</span>
							</div>
							<input style="padding-left: .8rem" {{ $isNewPayment ? '' : 'readonly' }} placeholder="{{ trans('gl-posting-settings::general.customer_classification') }}" name="customer_classification" value="{{ $CONTACT['class'] ?? '' }}" type="text" class="form-control">
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4 col-sm-6">
					<div class="form-group">
						<label for="name" class="form-control-label">{{ trans('gl-posting-settings::general.account') }}</label>
						<div class="input-group input-group-merge">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fas fa-university"></i>								
								</span>
							</div>
							<select style="padding-left: .8rem" {{ $isNewPayment ? '' : 'readonly' }} placeholder="{{ trans('gl-posting-settings::general.account') }}" name="account" class="form-control selectize-field">
								<option value="">Select Account</option>
								@foreach ($bank_accounts as $b_acc)
									<option value="{{ $b_acc->id }}" {{ isset($recon->account_id) && $recon->account_id == $b_acc->id ? 'selected' : '' }}>
										{{ $b_acc->name }} : {{ $b_acc->number }}
									</option>	
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-6">
					<div class="form-group">
						<label for="name" class="form-control-label">{{ trans('gl-posting-settings::general.paid_on') }}</label>
						<div class="input-group input-group-merge">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fas fa-university"></i>								
								</span>
							</div>
							<input style="padding-left: .8rem" {{ $isNewPayment ? '' : 'readonly' }} placeholder="{{ trans('gl-posting-settings::general.paid_on') }}" name="paid_on" value="{{ isset($recon->id) ? \Carbon\Carbon::parse($recon->transaction_time)->format('Y-m-d\TH:i:s') : '' }}" type="datetime-local" class="form-control">
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-6">
					<div class="form-group">
						<label for="name" class="form-control-label">{{ trans('gl-posting-settings::general.receipted_on') }}</label>
						<div class="input-group input-group-merge">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fas fa-university"></i>								
								</span>
							</div>
							<input style="padding-left: .8rem" placeholder="{{ trans('gl-posting-settings::general.receipted_on') }}" name="receipted_on" value="{{ isset($recon->id) ? (trim($recon->receipt_date) == "" ? \Carbon\Carbon::now()->format('Y-m-d\TH:i:s') : \Carbon\Carbon::parse($recon->receipt_date)->format('Y-m-d\TH:i:s')) : '' }}" type="datetime-local" class="form-control">
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4 col-sm-6">
					<div class="form-group">
						<label for="name" class="form-control-label">{{ trans('gl-posting-settings::general.amount_paid') }}</label>
						<div class="input-group input-group-merge">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fas fa-university"></i>								
								</span>
							</div>
							<input style="padding-left: .8rem" {{ $isNewPayment ? '' : 'readonly' }} placeholder="{{ trans('gl-posting-settings::general.amount_paid') }}" name="amount_paid" data-value="{{ !isset($payload) ? 0 :  (floatval($payload->TransAmount ?? ($payload->amount ?? 0))) }}" value="{{ !isset($payload) ? 0 : floatval($payload->TransAmount ?? ($payload->amount ?? 0)) }}" type="number" step="any" class="form-control text-align-right">
						</div>
					</div>
				</div>
				{{-- <div class="col-md-4 col-sm-6">
					<div class="form-group">
						<label for="name" class="form-control-label">{{ trans('gl-posting-settings::general.affected_invoices') }}</label>
						<div class="input-group input-group-merge">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fas fa-university"></i>								
								</span>
							</div>
							<select style="padding-left: .8rem" {{ $isNewPayment ? '' : 'readonly' }} multiple="true" placeholder="{{ trans('gl-posting-settings::general.affected_invoices') }}" name="affected_invoices[]" class="form-control selectize-field"
							 data-searchfield="number" data-labelfield="number" data-valuefield="id">
								@foreach ($isNewPayment ? [] : $recon->affected_invoices() as $inv)
									<option value="{{ $inv->id }}" selected>{{ $inv->number }}</option>	
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-6">
					<label for="name" class="form-control-label">{{ trans('gl-posting-settings::general.chart_of_accounts') }}</label>
					<div class="input-group input-group-merge bg-grey">
						<div class="input-group-prepend">
							<span class="input-group-text">
								<i class="fa fa-font"></i>
							</span>
						</div>
						<select data-url="{{ route('gl-posting-settings.get-de-accounts') }}/" required
							name="de_account_id" data-searchfield="code_name" data-labelfield="code_name" data-valuefield="id" placeholder="{{ trans('gl-posting-settings::general.chart_of_accounts') }}..." 
							class="form-control selectize-field form-control-lg">
							<option value="">Select Account...</option>
							@foreach($accounts as $acc)
								<option value="{{ $acc->id }}" {{ (isset($recon->id) && $recon->de_account_id == $acc->id) ? 'selected' : '' }}>{{ $acc->reference_str }}</option>
							@endforeach
						</select>
					</div>
				</div> --}}
				
				<div class="col-md-4 col-sm-6">
					<label for="name" class="form-control-label">{{ trans('gl-posting-settings::general.payment_mode') }}</label>
					<div class="input-group input-group-merge bg-grey">
						<div class="input-group-prepend">
							<span class="input-group-text">
								<i class="fa fa-font"></i>
							</span>
						</div>
						<select required	name="mode_of_payment" placeholder="{{ trans('gl-posting-settings::general.payment_mode') }}..." 
							class="form-control selectize-field form-control-lg" data-mode='{{ json_encode($payment_methods) }}' data-searchfield="name" data-labelfield="name" data-valuefield="code">
							<option value="">Select Payment Mode...</option>
							@foreach($payment_methods as $pay_method)
								<option value="{{ $pay_method['code'] }}" {{ (isset($recon->mode_of_payment) && $recon->mode_of_payment == $pay_method['code']) ? 'selected' : '' }}>{{ $pay_method['name'] }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-4 col-sm-6">
					<div class="form-group">
						<label for="name" class="form-control-label">{{ trans('gl-posting-settings::general.reference') }}</label>
						<div class="input-group input-group-merge">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fa fa-money-check"></i>
								</span>
							</div>
							<input style="padding-left: .8rem" {{ $isNewPayment ? '' : 'readonly' }} placeholder="{{ trans('gl-posting-settings::general.reference') }}" name="reference" value="{{ $recon->reference ?? '' }}" type="text" class="form-control">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-header with-border">
			<h3 class="card-title">{{ trans('gl-posting-settings::general.payment_distribution') }}</h3>
		</div>
		<div class="card-body">
			<div class="table-responsive custom">
				<table class="table table-flush table-hover">
					<thead>
						<tr>
							<th><i class="fa fa-ellipsis-h text-muted"></i></th>
							<th>{{ trans('gl-posting-settings::general.invoice') }}</th>
							<th>{{ trans('gl-posting-settings::general.item_description') }}</th>
							<th>{{ trans('gl-posting-settings::general.total_price') }}</th>
							<th>{{ trans('gl-posting-settings::general.payment_distribution') }}</th>
							{{-- <th>{{ trans('gl-posting-settings::general.account') }}</th> --}}
						</tr>
					</thead>
					<tbody id="item-rows-bd">
						@php($ALL_TOTAL = 0)
						@php($currency_code = '')
						@foreach($recon->consensus_items ?? [] as $item)
							<tr>
								<td>
									<span class="btn btn-sm btn-transparent text-danger remove-row"><i class="fas fa-trash"></i></span>
								</td>
								<td>
									<input type="hidden" name="row[{{ $item->id }}][id]" value="{{ $item->id }}" />
									@if(trim($item->document_id)!="")
										<a href="{{ route('invoices.edit', $item->document_id) }}">{{ $item->document_number }}</a>
										<input type="hidden" name="row[{{ $item->id }}][document_id]" value="{{ $item->invoice->id }}" />
									@else
										-
									@endif
								</td>
								<td>
									<span class="badge badge-sm badge-circle badge-secondary">{{ floatval($item->document_item->quantity ?? 1) }}</span> x {{ $item->item_name }}
									<p class="text-muted pt-1" style="font-family: 'Courier New', Courier, monospace">
										@if(trim($item->document_item->description ?? '') != '')
											<small>{{ $item->document_item->description ?? '' }}</small>
										@endif
									</p>
									<p>
										<small><span class="badge badge-info badge-pill">Unit Price</span> {{ $item->invoice->currency_code ?? 'KES' }} {{ number_format($item->document_item->price ?? '0', 2) }}</small><br>
										<small><span class="badge badge-primary badge-pill">Total Tax&nbsp;</span> {{ $item->invoice->currency_code ?? 'KES' }} {{ number_format($item->document_item->tax ?? '0', 2) }}</small>
									</p>
								</td>
								@php($amountPending = $item->invoice_amount_tax - $item->paid_amount)
								<td class="total-holder" data-total="{{ $amountPending }}">
									{{ $item->invoice->currency_code ?? 'KES'  }} {{ number_format($amountPending, 2) }}
								</td>
								@php($currency_code = $item->invoice->currency_code ?? 'KES')
								<td class="form-group">
									<input type="number" step="any" class="form-control form-control-sm text-right item-dist-amnt" min="0" name="row[{{ $item->id }}][distributed_amount]" value="{{ $item->payable_amount < 0 ? 0 : $item->payable_amount }}" placeholder="Payment Distribution" style="min-width: 175px"/>
								</td>
								{{-- <td class="form-group" style="width: 130px">
									@php($Acc = isset($item->account) ? $item->account : [])
									<select data-icon="fa-address-card" data-searchfield="code_name" data-labelfield="code_name" data-valuefield="id" class="form-control selectize-field" data-url="{{ route('gl-posting-settings.get-de-accounts') }}/" style="min-width: 175px" name="row[{{ $item->id }}][account]" placeholder="Select Account...">
										@if(isset($Acc->id))
											<option value="{{ $Acc->id }}" selected>{{ $Acc->code }}:{{ $Acc->name }}</option>
										@endif
									</select>
								</td> --}}
							</tr>
						@endforeach
						<tr class="d-none exceedAmnt-MSG">
							<td colspan="6">
								<div class="alert alert-danger">
									<i class="fas fa-exclamation-triangle"></i> The item total exceeds the amount paid!
								</div>
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr class="align-items-center border-top-1 tr-py">
							<td class="align-items-center" colspan="6">
								<div class="btn btn-transparent text-default w-100" id="add-new-row-btn">
									<i class="fas fa-plus"></i> Add Item
								</div>
							</td>
						</tr>
						<tr style="font-size: 1.4rem">
							<td colspan="3" class="text-align-right text-right"><strong>TOTAL</strong></td>
							<td colspan="1" class="text-align-right text-right"><strong>{{ $currency_code }} <span class="all-items-total"></span></strong></td>
							<td colspan="1" class="text-align-right text-right"><strong>{{ $currency_code }} <span class="distribution-items-total"></span></strong></td>
							<td colspan="1"></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
	@if(!isset($recon->id) || $recon->status != 'reconciled')
	<div class="card">
		<div class="card-footer">
			<div class="row float-right">
				{{ Form::saveButtons('gl-posting-settings.recon-index') }}
			</div>
		</div>
	</div>
	@endif
	{!! Form::close() !!}
@stop
@push('scripts_start')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css" integrity="sha512-bkB9w//jjNUnYbUpATZQCJu2khobZXvLP5GZ8jhltg7P/dghIrTaSJ7B/zdlBUT0W/LXGZ7FfCIqNvXjWKqCYA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.bootstrap4.min.css" integrity="sha512-MMojOrCQrqLg4Iarid2YMYyZ7pzjPeXKRvhW9nZqLo6kPBBTuvNET9DBVWptAo/Q20Fy11EIHM5ig4WlIrJfQw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js" integrity="sha512-pF+DNRwavWMukUv/LyzDyDMn8U2uvqYQdJN0Zvilr6DDo/56xPDZdDoyPDYZRSL4aOKO/FGKXTpzDyQJ8je8Qw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>	
	<script>
		flash_notification = true;
		var currentTotal = 0;
		var amountPaid;
		var calculateItemTotals = function(){
			var allTotal = 0;
			$('#item-rows-bd').find('.total-holder').each(function(){
				var total = $(this).data('total');
				allTotal+= parseFloat(total);
			});

			currentTotal = 0;

			$('#item-rows-bd').find('.item-dist-amnt').each(function(){
				var total = $(this).val();
				currentTotal+= parseFloat(total);
			});

			$('.all-items-total').text(numberWithCommas(allTotal.toFixed(2)));

			$isOverAmountPaid = parseFloat(amountPaid) < parseFloat(currentTotal);

			$('.distribution-items-total').text(numberWithCommas(currentTotal.toFixed(2))).parent().css('color', $isOverAmountPaid ? '#ff2200' : 'inherit');

			if($isOverAmountPaid){
				$('[data-target="#trigger-payment-modal"]').hide();
				$('.exceedAmnt-MSG').removeClass('d-none');
			}
			else{
				$('.exceedAmnt-MSG').addClass('d-none');
				$('[data-target="#trigger-payment-modal"]').show();
			}

			return currentTotal;
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
								`<div class="p-2"><i class="far ${$icon}"></i> ${item[$labelField]}</div>`
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

			if(locked){
				tis[0].selectize.lock();
			}

			$(tis).on('change', function(){
				var $val = $(this).val();

				if($(tis).hasClass('can-trigger-customer-change') && $.trim($val) != ""){
					$('#customer-changed-modal').modal('show');
				}

			});
		}

		var add_breakdown = function(){
			var randNo = Math.floor(Math.random() * -1000);
			var $row = $(`
				<tr>
					<td><span class="btn btn-sm btn-transparent text-danger remove-row"><i class="fas fa-trash"></i></span></td>
					<td></td>
					<td>
						<select data-icon="fa-box" data-searchfield="name" data-url="{{ route('gl-posting-settings.fetch-dist-items', $CONTACT['id'] ?? 0) }}/" data-labelfield="name" data-valuefield="id" class="form-control selectize-field item-field" name="new_row[${randNo}][item_id]" placeholder="Select Item..." required></select>
					</td>
					<td class="total-holder" data-total="0"></td>
					<td class="form-group">
						<input required="true" type="number" step="any" name="new_row[${randNo}][distributed_amount]" class="form-control form-control-sm text-right item-dist-amnt" min="0" value="0" placeholder="Payment Distribution..." style="min-width: 175px"/>
					</td>
					{{-- <td class="form-group">
						<select required="true" {{ $isNewPayment ? '' : 'readonly' }} data-icon="fa-address-card" data-searchfield="code_name" data-labelfield="code_name" data-valuefield="id" class="form-control selectize-field" data-url="{{ route('gl-posting-settings.get-de-accounts') }}/" style="min-width: 175px" name="new_row[${randNo}][account]" placeholder="Select Account..."></select>
					</td> --}}
				</tr>
			`);

			var $clone = $row.clone();

			$clone.find('.selectize-field').each(function(){
				if ($(this)[0].selectize) {
					$(this)[0].selectize.destroy();
				}
				selectizerNow(this);
			});

			$clone.find('select.item-field').on('change', function(){
				var idd = $(this).val();
				var itemText = $(this).children('option:selected').text();
				var idParts = idd.split('-');

				if(idParts.length > 1){
					$(this).parents('td').append(`<input type="hidden" name="new_row[${randNo}][invoice_id]" value="${$.trim(idParts[idParts.length - 1])}" />`);
					$(this).parents('td').append(`<input type="hidden" name="new_row[${randNo}][item_id]" value="${$.trim(idParts[1])}" />`);
					$(this).parents('td').append(`<input type="hidden" name="new_row[${randNo}][document_item_id]" value="${$.trim(idParts[0])}" />`);
				}
			});

			return $clone;
		}

		var changeReceiptValue = function(v){
			$('input[type="hidden"][name="send_receipt_to_customer"]').val(v);
		}

		$(function(){

			amountPaid = $('input[name="amount_paid"]').data('value') || 0;

			calculateItemTotals();
			$('#item-rows-bd').on('click', '.remove-row', function(){
				var tr = $(this).parents('tr');
				if(confirm("Remove this item from the payment distribution?")){
					tr.remove();
				}
				calculateItemTotals();
			});

			$('#add-new-row-btn').on('click', function(){
				var newRow = add_breakdown();
				$('#item-rows-bd').append(newRow);
			});

			$(this).find('.selectize-field').each(function() {
				if ($(this)[0].selectize) {
					$(this)[0].selectize.destroy();
				}
				selectizerNow(this);
			});

			$('.item-dist-amnt').on('keyup', function(){
				var amnt = $(this).val();
				var newTotal = calculateItemTotals();
			});

			$('#trigger-posting-details-form-submit').on('click', function(){
				$('#posting-details-form').find('button[type="submit"]').trigger('click');
			});
		});

		$('#flash-overlay-modal').modal();
	</script>
	<div class="modal" tabindex="-1" id="customer-changed-modal" style="z-index: 999999 !important;">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title">{{ trans('gl-posting-settings::general.change_customer_modal_title') }}</h5>
					<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="alert alert-warning">
						<i class="fas fa-exclamation-triangle fa-2x pull-left m-2"></i> {{ trans('gl-posting-settings::general.change_customer_modal_message') }}
					</div>
				</div>
				<div class="modal-footer">
					<span data-dismiss="modal" class="btn btn-outline-secondary">Cancel</span>
        	<button type="submit" class="btn btn-icon btn-success" id="trigger-posting-details-form-submit">{{ trans('gl-posting-settings::general.change_customer_modal_submit_btn') }}</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal" tabindex="-1" id="trigger-payment-modal" style="z-index: 999999 !important;">
		<div class="modal-dialog">
			<form class="modal-content" method="POST" action="{{ route('gl-posting-settings.post-payments', $recon->id ?? 'new') }}">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title">{{ trans('gl-posting-settings::general.post_payment_modal_title') }}</h5>
					<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="alert alert-warning">
						<i class="fas fa-exclamation-triangle fa-2x pull-left m-2"></i> {{ trans('gl-posting-settings::general.post_payment_modal_message') }}
					</div>
					<div class="row">
						<div class="form-group col-md-6">
							<label for="send_receipt_to_customer" class="form-control-label">Email Receipt</label> 
							<div role="tabpanel" aria-labelledby="-component-tab" class="tab-pane tab-example-result fade show active">
								<div data-toggle="buttons" class="btn-group btn-group-toggle radio-yes-no">
									<label class="btn btn-success active" data-value="1" onclick="changeReceiptValue(1)">
										Yes
										<input type="radio" name="send_receipt_to_customer">
									</label> 
									<label class="btn btn-danger" data-value="0" onclick="changeReceiptValue(0)">
										No
										<input type="radio" name="send_receipt_to_customer">
									</label>
								</div> 
								<input type="hidden" name="send_receipt_to_customer" value="1" />
							</div>
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

@endpush