@extends('layouts.admin')

@section('title', trans('customizations.customer_statement'))

@section('new_button')
	@if($customer && trim($customer)!="")
		<a target="_blank" href="{{ route('gl-posting-settings.customer-statements') }}?account_no={{ $customer }}&action=print" class="btn btn-secondary btn-sm">{{ trans('customizations.customer_statement') }}</a>
	@endif
	<span data-type="xlsx" class="btn btn-success btn-sm exporter-btn">{{ trans('general.export') }} Excel</span>
	<span data-type="pdf" class="btn btn-danger btn-sm exporter-btn">{{ trans('general.export') }} PDF</span>
@endsection

@section('content')
	<div id="app">
		<div class="card">
			<div class="card-header border-bottom-0" :class="[{'bg-gradient-info': bulk_action.show}]">
				{!! Form::open([
					'method' => 'GET',
					'route' => 'gl-posting-settings.customer-statements',
					'role' => 'form',
					'class' => 'mb-0'
				]) !!}
					<div class="align-items-center" v-if="!bulk_action.show">
						<div class="row mb-2 text-muted">
							<div class="col-sm-4"><strong>{{ trans('customizations.customer') }}</strong></div>
							<div class="col-sm-2"><strong>{{ trans('customizations.start_date') }}</strong></div>
							<div class="col-sm-2"><strong>{{ trans('customizations.end_date') }}</strong></div>
							<div class="col-sm-3"><strong>{{ trans('customizations.caution_money_status') }}</strong></div>
							<div class="col-sm-1"></div>
						</div>
						<div class="position-relative js-search-box-hidden row" style="height: 48.5px;">
							<div class="border-bottom-0 col-sm-4" style="z-index: 9;">
								<select data-url="{{ route('payment-plan.get-customers-json') }}/"
									name="account_no" data-customers="{{ json_encode($theContacts) }}"
									placeholder="{{ trans('customizations.customer') }}..."
									class="form-control selectize-field">
									<option value="">{{ trans('customizations.customer') }}...</option>
									@foreach ($theContacts as $cno)
										<option value="{{ $cno->reference }}" {{ $customer == $cno->reference ? 'selected' : '' }}>{{ $cno->reference_str }}</option>
									@endforeach
								</select>
							</div>
							<div class="col-sm-2">
								<input class="form-control" type="datetime-local" name="start_date" value="{{ $start_date }}" placeholder="Start Date..." />
							</div>
							<div class="col-sm-2">
								<input class="form-control" max="{{ \Carbon\Carbon::now()->addDays(1) }}" type="datetime-local" name="end_date" value="{{ $end_date }}" placeholder="End Date..." />
							</div>
							<div class="col-sm-3">
								@php($filter_bys = ['All'=>'', 'Debited'=>'invoice_total', 'Credited'=>'amount_paid'])
								<select name="filter_by" placeholder="{{ trans('customizations.caution_money_status') }}..."
									class="form-control selectize-field">
									<option value="">{{ trans('customizations.caution_money_status') }}...</option>
									@foreach ($filter_bys as $key=>$val)
										<option value="{{ $val }}" {{ $val == $filter_by ? 'selected' : '' }}>{{ $key }}</option>
									@endforeach
								</select>
							</div>
							<div class="col-sm-1">
								<button class="btn btn-success btn-lg btn-block" id="submit-this-form-btn"><i class="fas fa-arrow-right"></i></button>
							</div>
						</div>
					</div>
					{{ Form::bulkActionRowGroup('gl-posting-settings::general.reconsiliation', $bulk_actions ?? [], ['group' => 'gl-posting-settings', 'type' => 'bank_integration_payloads']) }}
				{!! Form::close() !!}
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-sm-4">
						<div class="card bg-gradient-warning border-0">
							<div class="card-body">
								<div class="row">
									<div class="col">
										<h5 class="text-uppercase text-muted mb-0 text-white">{{ trans('gl-posting-settings::general.cs_invoice_total') }}</h5> 
										<div class="dropdown-divider"></div> 
										<span class="h2 font-weight-bold mb-0 text-white">@money($cautionTotals->debit_total, setting('default.currency', 'KES'), true)</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="card bg-gradient-success border-0">
							<div class="card-body">
								<div class="row">
									<div class="col">
										<h5 class="text-uppercase text-muted mb-0 text-white">{{ trans('gl-posting-settings::general.cs_amount_paid') }}</h5> 
										<div class="dropdown-divider"></div> 
										<span class="h2 font-weight-bold mb-0 text-white">@money($cautionTotals->credit_total, setting('default.currency', 'KES'), true)</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="card bg-gradient-danger border-0">
							<div class="card-body">
								<div class="row">
									<div class="col">
										<h5 class="text-uppercase text-muted mb-0 text-white">{{ trans('gl-posting-settings::general.cs_balance_amount') }}</h5> 
										<div class="dropdown-divider"></div> 
										@php($amountDue = floatval($cautionTotals->debit_total)-floatval($cautionTotals->credit_total))
										<span class="h2 font-weight-bold mb-0 text-white">
											@money($amountDue,setting('default.currency', 'KES'), true)</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="table-responsive custom">
					<table class="table table-flush table-hover w-100" id="tbl-invoice-items">
						<thead class="thead-light">
							<tr class="table-head-line">
								<th>{{ trans('customizations.customer') }}</th>
								<th>{{ trans('customizations.reference') }}</th>
								<th>{{ trans('customizations.type') }}</th>
								<th>{{ trans('customizations.date') }}</th>
								<th>{{ trans('customizations.number') }}</th>
								<th>{{ trans('customizations.note') }}</th>
								<th class="text-right">{{ trans('customizations.debit') }}</th>
								<th class="text-right">{{ trans('customizations.credit') }}</th>
							</tr>
						</thead>
						<tbody>
							@php($badgecolor = ['Credit'=>'success', 'Debit'=>'danger'])
							@foreach($caution as $st)
								<tr class="align-items-center border-top-1 tr-py">
									<td>{{ $st->name }}</td>
									<td>{{ $st->account_no }}</td>
									<td><span class="badge badge-pill bg-{{ $badgecolor[$st->cr_dr] }} text-white">{{ $st->entry_type }}</span></td>
									<td>{{ $st->trans_date }}</td>
									<td>{{ $st->invoice_no }}</td>
									<td>{{ $st->note }}</td>
									<td class="text-right">@money($st->invoice_total, setting('default.currency', 'KES'), true)</td>
									<td class="text-right">@money($st->amount_paid, setting('default.currency', 'KES'), true)</td>
								</tr>
							@endforeach
							@if(count($caution) == 0)
								<tr>
									<td colspan="6"><span class="text-muted">No Records Found.</span></td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
			<div class="card-footer table-action">
				<div class="row align-items-center">
					@php($limits = json_decode('{"10":"10","25":"25","50":"50","100":"100"}'))
					@include('partials.admin.pagination', ['items' => $caution, 'limits'=>$limits])
				</div>
			</div>
		</div>
	</div>

	<div class="card">
		<div class="card-header d-flex justify-content-between">
			<strong>AGE ANALYSIS</strong>
			<div class="btn-grp">
				<span data-target="#add-age-band-modal" data-toggle="modal" class="btn btn-success btn-sm">{{ trans('gl-posting-settings::general.add_age_band') }}</span>
			</div>
		</div>
		<div class="card-body">
			@if (count($ageAnalysis) == 0)
				<small class="p-2 text-muted">NO AGE BANDS ADDED YET</small>
			@else
			<div class="d-flex justify-content-around">
				@php($total_amnt = 0)
				@foreach ($ageAnalysis as $age)
					<div class="d-flex flex-column text-align-center">
						<h5>{{ $age->title }} 
							<span data-toggle="modal" data-band="{{ json_encode($age) }}" data-target="#remove-age-band-modal" style="padding: 3px; cursor: pointer;" class="text-danger">
								<i class="fas fa-trash"></i>
							</span>
					</h5>
						<p>@money(abs($age->value), setting('default.currency', 'KES'), true)</p>
					</div>
				@endforeach
				<div class="d-flex flex-column text-align-center">
					<h5>TOTAL</h5>
					<p>{{ $total_amnt }}</p>
				</div>
			</div>
			@endif
		</div>
	</div>
@endsection
@push('scripts_start')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css" integrity="sha512-bkB9w//jjNUnYbUpATZQCJu2khobZXvLP5GZ8jhltg7P/dghIrTaSJ7B/zdlBUT0W/LXGZ7FfCIqNvXjWKqCYA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.bootstrap4.min.css" integrity="sha512-MMojOrCQrqLg4Iarid2YMYyZ7pzjPeXKRvhW9nZqLo6kPBBTuvNET9DBVWptAo/Q20Fy11EIHM5ig4WlIrJfQw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js" integrity="sha512-pF+DNRwavWMukUv/LyzDyDMn8U2uvqYQdJN0Zvilr6DDo/56xPDZdDoyPDYZRSL4aOKO/FGKXTpzDyQJ8je8Qw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>	
	<script>
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
		$(function(){
			var selectizerNow = function(tis) {
				var $url = $(tis).data('url');
				if ($url) {
					$(tis).selectize({
						valueField: "reference",
						labelField: "reference_str",
						searchField: "reference_str",
						create: false,
						onInitialize: function() {
							this.trigger('change', this.getValue(), true);
						},
						onChange: function(value, isOnInitialize) {
							
						},
						render: {
							option: function(item, escape) {
								return (
									`<div class="p-2"><i class="far fa-address-card"></i> ${item.reference_str}</div>`
								);
							},
						},
						load: function(query, callback) {
							if (!query.length) return callback();
							$.ajax({
								url: $url + encodeURIComponent(query),
								type: "GET",
								error: function() {
									callback();
								},
								success: function(res) {
									callback(res);
								},
							});
						},
					});
				} else {
					$(tis).selectize();
				}
			}

			$(this).find('.selectize-field').each(function() {
				if ($(this)[0].selectize) {
					$(this)[0].selectize.destroy();
				}
				selectizerNow(this);
			});

			$('#remove-age-band-modal').on('show.bs.modal', function(e){
				var $btn = $(e.relatedTarget);
				var band = $btn.data('band');

				$('.band-to-remove').text(band.title);

				$(this).find('form').attr('action', "{{ route('gl-posting-settings.delete-age-band') }}/"+band.id);
				$(this).find('form').prop('action', "{{ route('gl-posting-settings.delete-age-band') }}/"+band.id);
			});

			$('.exporter-btn').on('click', function(){
				var $type = $(this).data('type');
				$('#submit-this-form-btn').parent().append(`<input type="hidden" name="format" value="${$type}" />`);
				$('#submit-this-form-btn').parent().append(`<input type="hidden" name="action" value="export" />`);
				$('#submit-this-form-btn').trigger('click');
			});
		});
	</script>
	<div class="modal" tabindex="-1" id="add-age-band-modal" style="z-index: 999999 !important;">
		<form method="POST" action="{{ route('gl-posting-settings.add-age-band') }}" class="modal-dialog">
			<div class="modal-content">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title">Add Age Analysis Band</h5>
					<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>Age Band Title</label>
						<div class="input-group input-group-merge">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fas fa-font"></i>								
								</span>
							</div>
							<input style="padding-left: .8rem" placeholder="Title..." name="title" type="text" required class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label>Lower Value <small>(In Days)</small></label>
						<div class="input-group input-group-merge">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fas fa-arrow-up"></i>								
								</span>
							</div>
							<input style="padding-left: .8rem" placeholder="Lower..." name="lower" type="number" min="0" value="0" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label>Upper Value <small>(In Days)</small></label>
						<div class="input-group input-group-merge">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fas fa-arrow-down"></i>								
								</span>
							</div>
							<input style="padding-left: .8rem" placeholder="Upper..." name="upper" type="number" required class="form-control">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button class="btn btn-success">Save</button>
				</div>
			</div>
		</form>
	</div>
	<div class="modal" tabindex="-1" id="remove-age-band-modal" style="z-index: 999999 !important;">
		<form method="POST" class="modal-dialog">
			<div class="modal-content">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title">Delete Age Analysis Band: <strong class="band-to-remove"></strong></h5>
					<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="alert alert-warning">
						<i class="fa fa-exclamation-triangle"></i> Are you sure you want to remove the band <strong class="band-to-remove"></strong>?
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button class="btn btn-danger">DELETE</button>
				</div>
			</div>
		</form>
	</div>
@endpush