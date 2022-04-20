@extends('layouts.admin')

@section('title', trans('gl-posting-settings::general.name'))

@section('new_button')
	<span class="btn btn-success btn-sm" data-target="#update-new-gl-setting" data-toggle="modal">{{ trans('general.add_new') }}</span>
@endsection

@section('content')
	<div id="app">
		<div class="card">
			@if(count($gl_configs) == 0)
				<div class="row align-items-center">
					<div class="col-xs-12 col-sm-6 text-center p-5">
						<i class="fas fa-cogs fa-10x pt-10 text-warning"></i>
					</div>
					<div class="col-xs-12 col-sm-6 text-center p-5">
						<p class="text-justify description" style="font-size: 1.3rem">
							No GL Posting configurations exist.
						</p>
					</div>
				</div>
			@else
				<div class="card-header border-bottom-0" :class="[{'bg-gradient-info': bulk_action.show}]">
					{!! Form::open([
						'method' => 'GET',
						'route' => 'gl-posting-settings.index',
						'role' => 'form',
						'class' => 'mb-0'
					]) !!}
						<div class="align-items-center" v-if="!bulk_action.show">
							<div class="position-relative js-search-box-hidden" style="height: 48.5px;">
								<div class="border-bottom-0 w-100 position-absolute left-0 right-0" style="z-index: 9;">
									<input type="text" value="{{ $searchTerm }}" name="search" placeholder="Search or filter results..." class="form-control" />
								</div>
							</div>
						</div>

						{{ Form::bulkActionRowGroup('gl-posting-settings::general.name', $bulk_actions ?? [], ['group' => 'gl-posting-settings', 'type' => 'sm_system_approval_entities']) }}
					{!! Form::close() !!}
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-flush table-hover ellipsized">
							<thead class="thead-light">
								<tr class="row table-head-line">
									<th class="col-lg-1 col-xs-2 col-sm-1 col-md-1">{{ Form::bulkActionAllGroup() }}</th>
									<th class="col-lg-2 col-xs-8 col-sm-5 col-md-2" data-payment_type="{{ trans('gl-posting-settings::general.payment_type') }}">@sortablelink('payment_type', trans('gl-posting-settings::general.payment_type'))</th>
									<th class="col-lg-1 col-md-1 col-sm-4 d-none d-sm-block" data-account_type="{{ trans('gl-posting-settings::general.account_type') }}">@sortablelink('account_type', trans('gl-posting-settings::general.account_type_abbr'))</th>
									<th class="col-lg-2 col-md-3 d-none d-md-block" data-income_acc_name_code="{{ trans('gl-posting-settings::general.income_ac_to_credit') }}">@sortablelink('income_ac_to_credit', trans('gl-posting-settings::general.income_ac_to_credit'))</th>
									<th class="col-lg-2 col-md-2 d-none d-md-block" data-cash_book_name_code="{{ trans('gl-posting-settings::general.cash_book_ac_to_dedit') }}">@sortablelink('cash_book_ac_to_dedit', trans('gl-posting-settings::general.cash_book_ac_to_dedit'))</th>
									<th class="col-lg-2 col-md-2 d-none d-md-block" data-debtors_acc_name_code="{{ trans('gl-posting-settings::general.debtors_acc') }}">@sortablelink('debtors_acc', trans('gl-posting-settings::general.debtors_acc'))</th>
									<th class="col-lg-1 col-lg-1 d-none d-lg-block escapee" data-status="{{ trans('gl-posting-settings::general.active') }}">@sortablelink('active', trans('gl-posting-settings::general.active'))</th>
									<th class="col-lg-1 col-xs-2 col-sm-2 col-md-1 escapee">{{ trans('general.actions') }}</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($gl_configs as $app)
									<tr class="row align-items-center border-top-1">
										<td class="col-lg-1 col-xs-2 col-sm-1 col-md-1">{{ Form::bulkActionGroup($app->id, $app->payment_type) }}</td>
										<td class="col-lg-2 col-xs-8 col-sm-5 col-md-2">{{ $app->payment_type }}</td>
										<td class="col-lg-1 col-md-1 col-sm-4 d-none d-sm-block">{{ $app->account_type }}</td>
										<td class="col-lg-2 col-md-3 d-none d-md-block">{{ $app->income_acc_name_code }}</td>
										<td class="col-lg-2 col-md-2 d-none d-md-block">{{ $app->cash_book_name_code }}</td>
										<td class="col-lg-2 col-md-2 d-none d-md-block">{{ $app->debtors_acc_name_code }}</td>
										<td class="col-lg-1 d-none d-lg-block escapee">
											<label class="custom-toggle d-inline-block">
												<input type="checkbox" data-url='{{ route("gl-posting-settings.toggle-gl-setting-active", $app->id) }}' name="status" {{ $app->status==1 ? 'checked="checked"' : '' }} /> 
												<span data-label-off="No" data-label-on="Yes" class="custom-toggle-slider rounded-circle status-green"></span>
											</label>
										</td>
										<td class="col-lg-1 col-xs-2 col-sm-2 col-md-1 escapee">
											<div class="dropdown">
												<a class="btn btn-neutral btn-sm text-light items-align-center p-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													<i class="fa fa-ellipsis-h text-muted"></i>
												</a>
												<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
													<span class="dropdown-item text-success" data-target="#update-new-gl-setting" data-toggle="modal" data-data="{{ json_encode($app) }}">
														<i class="fas fa-check"></i> {{ trans('gl-posting-settings::general.edit') }}
													</span>
													<span class="dropdown-item text-primary" data-target="#gl-details-modal" data-toggle="modal" data-data="{{ json_encode($app) }}">
														<i class="fas fa-info-circle"></i> {{ trans('gl-posting-settings::general.details') }}
													</span>
												</div>
											</div>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
				<div class="card-footer table-action">
					<div class="row align-items-center">
						@php($limits = json_decode('{"10":"10","25":"25","50":"50","100":"100"}'))
						@include('partials.admin.pagination', ['items' => $gl_configs, 'limits'=>$limits])
					</div>
				</div>
			@endif
		</div>
	</div>
@stop
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

			$('.custom-toggle input[type="checkbox"]').on('change', function(){
				var active  = $(this).is(":checked");
				var url = $(this).data('url');
				$.ajax({
					url: url,
					dataType: 'json',
					method: 'POST',
					data: {
						status: active ? 1 : 0,
						_token: window.Laravel.csrfToken
					},
					success: function(js){
						console.log(js.status);
					}
				});
			});

			$('#gl-details-modal').on('shown.bs.modal', function (e) {
				var target = $(e.relatedTarget);
				var data = target.data('data');
				var table = $(target).parents('table');

				$(this).find('.modal-title').text(`Details for ${data.payment_type} payment type`);
				var $listgroup = $(`<div class="list-group"></div>`);

				$.each(data, function(d,t){
					var title = table.find('th[data-'+d+']').data(d);
					if($.trim(title) != ''){
						$listgroup.append(`
							<div class="p-1 text-muted">
								<h6>${title}</h6>
								<span>${d== 'status' ? (t==1 ? 'Yes' : 'No') : t}</span>
							</div>
						`);
					}
				});

				$(this).find('.modal-body').empty();
				$(this).find('.modal-body').append($listgroup);
			});

			$('#update-new-gl-setting').on('shown.bs.modal', function (e) {
				var target = $(e.relatedTarget);
				var data = target.data('data');

				$(this).find('.modal-title').text(data ? 'Update GL Setting' : 'Add GL Setting');
				$(this).find('input[name="gl_id"]').val(data ? data.id : '');	

				$(this).find('.modal-body').find('select,input').val('');
				
				if(data){
					$(this).find('.modal-body input').each(function(){
						var fl = $(this).attr('name');
						var type = $(this).attr('type');

						$(this).val(data[fl]);
						type == "checkbox" ? (data[fl] == 1 ? $(this).prop('checked', 'checked').attr('checked', 'checked') : $(this).removeProp('checked').removeAttr('checked')) : null;
					});

					$(this).find('select').each(function(){
						var fl = $(this).attr('name');
						var fName = $(this).data('name');
						$(this).val(data[fl]);
						var hasURL = $(this).data('url');

						if($.trim(hasURL)!=""){
							$(this).empty();
							$(this).data('default', fl);
							$(this).append(`<option value="${data[fl]}" selected>${data[fName]}</option>`);
							$(this).trigger('change');
							console.log(hasURL);
						}
					});
				}

				$(this).find('.selectize-field').each(function(){
					if($(this)[0].selectize){
						$(this)[0].selectize.destroy();
					}
					selectizerNow(this);
				});
			})
		});
	</script>
	<div class="modal" tabindex="-1" id="gl-details-modal" style="z-index: 999999 !important;">
		<div class="modal-dialog">
			<div class="modal-content">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title"></h5>
					<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body"></div>
			</div>
		</div>
	</div>
	<div class="modal" tabindex="-1" id="update-new-gl-setting" style="z-index: 999999 !important;">
		<div class="modal-dialog">
			<form class="modal-content" method="POST">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title"></h5>
					<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>{{ trans('gl-posting-settings::general.payment_type') }}</label>
						<input type="text" class="form-control" name="payment_type" placeholder="{{ trans('gl-posting-settings::general.payment_type') }}" />
					</div>
					<div class="form-group">
						<label>{{ trans('gl-posting-settings::general.account_type') }}</label>
						<select class="form-control selectize-field" name="account_type" placeholder="{{ trans('gl-posting-settings::general.account_type') }}...">
							<option value="GL">General Ledger</option>
							<option value="C">Cash Book</option>
						</select>
					</div>
					<div class="form-group">
						<label>{{ trans('gl-posting-settings::general.income_ac_to_credit') }}</label>
						<select name="income_ac_to_credit" data-name="income_acc_name" class="form-control selectize-field" placeholder="{{ trans('gl-posting-settings::general.income_ac_to_credit') }}..." data-url="{{ route('gl-posting-settings.get-accounts-json') }}/"></select>
					</div>
					<div class="form-group">
						<label>{{ trans('gl-posting-settings::general.cash_book_ac_to_dedit') }}</label>
						<select name="cash_book_ac_to_dedit" data-name="cash_book_name" class="form-control selectize-field" placeholder="{{ trans('gl-posting-settings::general.cash_book_ac_to_dedit') }}..." data-url="{{ route('gl-posting-settings.get-accounts-json') }}/"></select>
					</div>
					<div class="form-group">
						<label>{{ trans('gl-posting-settings::general.debtors_acc') }}</label>
						<select name="debtors_acc" data-name="debtors_acc_name" class="form-control selectize-field" placeholder="{{ trans('gl-posting-settings::general.debtors_acc') }}..." data-url="{{ route('gl-posting-settings.get-accounts-json') }}/"></select>
					</div>
					<div class="form-group">
						<label><input type="checkbox" name="status" value="1" checked="checked" /> {{ trans('gl-posting-settings::general.active') }}</label>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button class="btn btn-success">Save changes</button>
				</div>
				<input type="hidden" name="gl_id" value="" />
			</form>
		</div>
	</div>
@endpush