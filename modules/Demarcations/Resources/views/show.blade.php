@extends('layouts.admin')

@section('title', $demarcation->name)

@section('new_button')
@stack('edit_button_start')
@can('create-demarcations-main')
	<a href="{{ route('demarcations.edit', [$type, $demarcation->id]) }}" class="btn btn-white btn-sm">
		{{ trans('general.edit') }}
	</a>
	@if ($type == 'region')
		<span class="btn btn-warning btn-sm" data-target="#add-subregion-modal" data-toggle="modal">
			{{ (isset($demarcation->subregion) && isset($demarcation->subregion->demarcation)) ? 
				trans('church::general.change_subregion') : trans('demarcations::general.add_subregion') }}
		</span>
	@endif
	
@endcan
@stack('edit_button_end')
@stack('add_pastor_button_start')
@can('update-demarcations-main')
	<span class="btn btn-default btn-sm" data-target="#set-overseer-modal" data-toggle="modal">{{ trans('demarcations::general.set_overseer') }}</span>
@endcan
@stack('add_pastor_button_end')
@endsection

@section('content')
	<div class="row">
		<div class="col-xl-3">
			{{-- <ul class="list-group mb-4">
				@stack('customer_invoices_count_start')
				<li class="list-group-item d-flex justify-content-between align-items-center border-0">
					{{ trans_choice('general.invoices', 2) }}
					<span class="badge badge-primary badge-pill">{{ $counts['invoices'] }}</span>
				</li>
				@stack('customer_invoices_count_end')

				@stack('customer_transactions_count_start')
				<li class="list-group-item d-flex justify-content-between align-items-center border-0 border-top-1">
					{{ trans_choice('general.transactions', 2) }}
					<span class="badge badge-primary badge-pill">{{ $counts['transactions'] }}</span>
				</li>
				@stack('customer_transactions_count_end')
			</ul> --}}

			<ul class="list-group mb-4">
				@stack('demarcations_start')
				<li class="list-group-item border-0 border-top-1">
					<div class="font-weight-600">{{ trans('general.name') }}</div>
					<div><small class="long-texts" title="{{ $demarcation->name }}">{{ $demarcation->name }}</small></div>
				</li>
				@stack('demarcations_end')

				@stack('demarcations_code_start')
				<li class="list-group-item border-0 border-top-1">
					<div class="font-weight-600">{{ trans('demarcations::general.code') }}</div>
					<div><small class="long-texts" title="{{ $demarcation->code }}">{{ $demarcation->code }}</small></div>
				</li>
				@stack('demarcations_code_end')

				@if($type == "subregion")
					@stack('demarcations_region_start')
					<li class="list-group-item border-0 border-top-1">
						<div class="font-weight-600">{{ trans_choice('church::general.region', 1) }}</div>
						<div>
							@if(isset($demarcation->parent) && isset($demarcation->parent->parent))
								<a class="long-texts" href="{{ route('church.show', $demarcation->parent->parent->id) }}">{{ $demarcation->parent->parent->name }}</a>
							@else
								<span class="badge badge-warning badge-sm">Not Set</span>
							@endif
						</div>
					</li>
					@stack('demarcations_region_end')
				@endif

	
				@stack('church_pastors_start')
				<li class="list-group-item border-0 border-top-1">
					<div class="font-weight-600">{{ trans_choice('demarcations::general.overseer', 1) }}</div>
					<div>
						@if(!empty($demarcation->overseer))
							<a class="long-texts text-sm" href="{{ route('church.pastors.show', $demarcation->overseer->pastor_id) }}">
								<small class="badge bg-dark text-white badge-pill">{{ $demarcation->overseer->rank->rank }}</small> <span class="text-muted">{{ $demarcation->overseer->pastor->contact->name }}</span>
							</a>
						@else
							<span class="badge badge-warning badge-sm">Not Set</span>
						@endif
					</div>
				</li>
				@stack('church_pastors_end')
			</ul>
		</div>
		<div class="col-xl-9">
			<div class="row">
				<div class="col-md-12">
					<div class="nav-wrapper">
						<ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
							@stack('customer_subregion_tab_start')
							<li class="nav-item">
								<a class="nav-link mb-sm-3 mb-md-0 active" id="subregion-tab" data-toggle="tab" href="#subregion-content" role="tab" aria-controls="subregion-content" aria-selected="true">
									{{ trans_choice('demarcations::general.subregion', 2) }}
								</a>
							</li>
							@stack('customer_subregion_tab_end')

							@if($type == "subregion")
								@stack('customer_subregion_history_tab_start')
								<li class="nav-item">
									<a class="nav-link mb-sm-3 mb-md-0" id="subregion_history-tab" data-toggle="tab" href="#subregion_history-content" role="tab" aria-controls="subregion_history-content" aria-selected="true">
										{{ trans('demarcations::general.subregion_history') }}
									</a>
								</li>
								@stack('customer_subregion_history_tab_end')		
							@endif
							
							@stack('customer_overseer_history_tab_start')
							<li class="nav-item">
								<a class="nav-link mb-sm-3 mb-md-0" id="overseer_history-tab" data-toggle="tab" href="#overseer_history-content" role="tab" aria-controls="overseer_history-content" aria-selected="false">
									{{ trans('demarcations::general.overseer_history') }}
								</a>
							</li>
							@stack('customer_overseer_history_tab_end')
				
							@stack('customer_church_statements_tab_start')
							<li class="nav-item">
								<a class="nav-link mb-sm-3 mb-md-0" id="church_statements-tab" data-toggle="tab" href="#church_statements-content" role="tab" aria-controls="church_statements-content" aria-selected="false">
									{{ trans_choice('demarcations::general.statement', 2) }}
								</a>
							</li>
							@stack('customer_church_statements_tab_end')
						</ul>
					</div>				
					<div class="card">
						<div class="tab-content" id="cutomer-tab-content">
							@stack('customer_overseer_history_content_start')
							<div class="tab-pane fade" id="overseer_history-content" role="tabpanel" aria-labelledby="overseer_history-tab">
								<div class="table-responsive custom">
									<table class="table table-flush table-hover" id="tbl-overseer_history">
										<thead class="thead-light">
											<tr class="table-head-line">
												<th class="">{{ trans_choice('church::general.pastor', 1) }}</th>
												<th class="">{{ trans('church::general.rank') }}</th>
												<th class="">{{ trans('church::general.position') }}</th>
												<th class="">{{ trans_choice('church::general.start', 1) }}</th>
												<th class="">{{ trans_choice('church::general.end', 1) }}</th>
											</tr>
										</thead>
										<tbody>
											@foreach($demarcation->pastors as $r)
												<tr>
													<td>{{ $r->pastor->contact->name }}</td>
													<td>{{ $r->rank->rank }}</td>
													<td>{{ $r->rank->position }}</td>
													<td>{{ $r->created_at }}</td>
													<td>
														@if(trim($r->deleted_at) != "") 
															{{ $r->deleted_at }}
														@else 
															<small class="badge badge-success">CURRENT</small>
														@endif
													</td>
												</tr>
											@endforeach
											@if (empty($church->pastor_history))
												<tr class="align-items-center border-top-1 tr-py">
													<td colspan="5 text-muted">No records found.</td>
												</tr>
											@endif
										</tbody>
									</table>
								</div>
							</div>
							@stack('customer_overseer_history_content_end')
							@stack('customer_subregion_content_start')
							<div class="tab-pane fade show active" id="subregion-content" role="tabpanel" aria-labelledby="subregion-tab">
								<div class="table-responsive custom">
									<table class="table table-flush table-hover" id="tbl-subregion">
										<thead class="thead-light">
											<tr class="table-head-line">
												<th class="">{{ trans_choice('general.name', 1) }}</th>
												<th class="">{{ trans_choice('demarcations::general.code', 1) }}</th>
												<th class="">{{ trans_choice('demarcations::general.overseer', 1) }}</th>
												<th class="">{{ trans_choice('demarcations::general.church', 2) }}</th>
											</tr>
										</thead>
										<tbody>
											
												@foreach ($demarcation->children as $item)
													<tr class="border-top-1 tr-py">
														<td><a href="{{ route('demarcations.show', [$type == 'region' ? 'subregion' : 'church', $item->demarcation->id]) }}">{{ $item->demarcation->name }}</a></td>
														<td><a href="{{ route('demarcations.show', [$type == 'region' ? 'subregion' : 'church', $item->demarcation->id]) }}">{{ $item->demarcation->code }}</a></td>
														<td>{{ isset($item->demarcation->overseer) ? $item->demarcation->overseer->rank->position.' '.$item->demarcation->overseer->pastor->contact->name : 'Not Set' }}</td>
														<td>{{ $item->demarcation->children->count() }}</td>
													</tr>
												@endforeach
												@if($demarcation->children->count() == 0)
												<tr class="align-items-center border-top-1 tr-py">
													<td colspan="4" class="text-muted">No records found.</td>
												</tr>
												@endif
												
										</tbody>
									</table>
								</div>
							</div>
							@stack('customer_subregion_content_end')
							@if($type == "subregion")
								@stack('customer_subregion_history_content_start')
								<div class="tab-pane fade show" id="subregion_history-content" role="tabpanel" aria-labelledby="subregion_history-tab">
									<div class="table-responsive custom">
										<table class="table table-flush table-hover" id="tbl-subregion_history">
											<thead class="thead-light">
												<tr class="table-head-line">
													<th class="">{{ trans_choice('general.name', 1) }}</th>
													<th class="">{{ trans_choice('demarcations::general.code', 1) }}</th>
													<th class="">{{ trans_choice('demarcations::general.overseer', 1) }}</th>
													<th class="">{{ trans_choice('demarcations::general.church', 2) }}</th>
												</tr>
											</thead>
											<tbody>
												<tr class="align-items-center border-top-1 tr-py">
													<td colspan="5 text-muted">No records found.</td>
												</tr>
										</tbody>
									</table>
								</div>
							</div>
							@stack('customer_subregion_history_content_end')
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
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
<div class="modal fade show" id="add-subregion-modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{route('demarcations.store', 'subregion')}}" method="post">
				@csrf
				<div class="modal-header border-bottom" style="background-color: #f8f9fe;">
					{{ trans('demarcations::general.add_subregion') }}
				</div>
				<div class="modal-body">
					{{ Form::textGroup('name', trans('general.name'), 'font', ['required'=>'required' ]) }}

					<input type="hidden" name="region_id" value="{{ $demarcation->id }}" />
					<input type="hidden" name="type" value="Sub Region" />
				</div>
				<div class="modal-footer border-top" style="background-color: #f8f9fe;">
					<span class="btn btn-white" data-dismiss="modal">Close</span>
					<button type="submit" class="btn btn-success">Add</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade show" id="set-overseer-modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{route('demarcations.set-overseer', $demarcation->id)}}" method="post">
				@csrf
				<div class="modal-header border-bottom" style="background-color: #f8f9fe;">
					{{ trans('demarcations::general.set_overseer') }}
				</div>
				<div class="modal-body">
					<div class="form-group form-group-lg">
						<label class="control-label">{{ trans_choice('church::general.pastor', 1) }}</label>
						<div class="input-group input-group-merge ">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fa fa-user"></i>
								</span>
							</div>
							<select data-url="{{ route('church.fetch_pastors') }}/" name="pastor_id" 
								placeholder="{{ trans_choice('church::general.pastor', 1) }}..."
								class="form-control selectize-field" data-searchfield="name" data-labelfield="name" data-valuefield="id">
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer border-top" style="background-color: #f8f9fe;">
					<span class="btn btn-white" data-dismiss="modal">Close</span>
					<button type="submit" class="btn btn-success">Add</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
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
							`<div class="p-2 pt-3 pb-3"><i class="far ${$icon}"></i> ${item[$labelField]}</div>`
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
		$(this).find('.selectize-field').each(function() {
			if ($(this)[0].selectize) {
				$(this)[0].selectize.destroy();
			}
			selectizerNow(this);
		});
	});
</script>
	{{-- <script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script> --}}
@endpush
