@extends('layouts.admin')

@section('title', $pastor->contact->name)

@section('new_button')


@stack('edit_button_start')

<a href="{{ route('church.pastors.edit', $pastor->id) }}" class="btn btn-white btn-sm">
	{{ trans('general.edit') }}
</a>
<span class="btn btn-white btn-sm" data-target="#pastor-history-modal" data-toggle="modal">{{ trans('church::general.add_pastor_history') }}</span>


@stack('edit_button_end')
@endsection

@section('content')
<div class="row">
	<div class="col-xl-3">
		<ul class="list-group mb-3 ">
			<li class="list-group-item border-0">
				<div class="p-2">
					<img src="{{$pastor->picture}}" class="w-100" />
				</div>
			</li>

		</ul>


		<ul class="list-group mb-4">
			@stack('pastor_phone_start')
			<li class="list-group-item border-0 border-top-1">
				<div class="font-weight-600">{{ trans('general.phone') }}</div>
				<div><small class="long-texts" title="{{ $pastor->contact->phone }}">{{ $pastor->contact->phone }}</small></div>
			</li>
			@stack('pastor_phone_end')

			@stack('pastor_gender_start')
			<li class="list-group-item border-0 border-top-1">
				<div class="font-weight-600">{{ trans('church::general.gender') }}</div>
				<div><small class="long-texts" title="{{ $pastor->gender }}">{{ $pastor->gender}}</small></div>
			</li>
			@stack('pastor_gender_end')
			@stack('pastor_church_start')
			<li class="list-group-item border-0 border-top-1">
				<div class="font-weight-600">{{ trans('church::general.name') }}</div>
				<div>
					<small class="long-texts" title="{{ $pastor->history->church->name ?? 'Not Set' }}">
					{{ $pastor->history->church->name ?? 'Not Set' }}
					</small>
				</div>
			</li>
			@stack('pastor_church_end')

			@stack('pastor_ranking_start')
			<li class="list-group-item border-0 border-top-1">
				<div class="font-weight-600">{{ trans('church::general.position') }}</div>
				<div><small>{{ $pastor->history->position ?? 'Not Set' }}</small></div>

			</li>
			@stack('pastor_ranking_end')
			@stack('pastor_ranking_start')
			<li class="list-group-item border-0 border-top-1">
				<div class="font-weight-600">{{ trans('church::general.rank') }}</div>
				<div><small>{{ $pastor->history->rank ?? 'Not Set' }}</small></div>

			</li>
			@stack('pastor_ranking_end')
		</ul>


	</div>

	<div class="col-xl-9">
		<div class="row">
			<div class="col-md-12">
				<div class="nav-wrapper">
					<ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
						@stack('pastor_history_tab_start')
						<li class="nav-item">
							<a class="nav-link mb-sm-3 mb-md-0 active" id="pastor_history-tab" data-toggle="tab" href="#pastor_history-content" role="tab" aria-controls="pastor_history-content" aria-selected="false">
								{{ trans('church::general.pastor_history') }}
							</a>
						</li>
						@stack('pastor_history_tab_end')
						@stack('pastor_statements_tab_start')
						<li class="nav-item">
							<a class="nav-link mb-sm-3 mb-md-0" id="history-tab" data-toggle="tab" href="#pastor-statement-content" role="tab" aria-controls="pastor-history-content" aria-selected="true">
								{{ trans_choice('church::general.pastor_statement', 2) }}
							</a>
						</li>
						@stack('pastor_statement_tab_end')

					</ul>
				</div>

				<div class="card">
					<div class="tab-content" id="cutomer-tab-content">
						@stack('customer_pastor_history_content_start')
						<div class="tab-pane fade show active" id="pastor_history-content" role="tabpanel" aria-labelledby="pastor-history-tab">
							<div class="table-responsive custom">
								<table class="table table-flush table-hover" id="tbl-pastor-history">
									<thead class="thead-light">
										<tr class="table-head-line">
											<th class="">{{ trans('church::general.rank') }}</th>
											<th class="">{{ trans('church::general.position') }}</th>
											<th class="">{{ trans('church::general.name') }}</th>
											<th class="">{{ trans_choice('general.start', 1) }}</th>
											<th class="">{{ trans_choice('general.end', 1) }}</th>
										</tr>
									</thead>
									<tbody>
										@foreach($pastor->rank_history as $r)
										<tr>
											<td>{{ $r->rank }}</td>
											<td>{{ $r->position }}</td>
											<td>{{ $r->church->name ?? '-' }}</td>
											<td>{{ $r->created_at }}</td>
											<td>
												@if(trim($r->deleted_at) != "")
												{{ $r->deleted_at }}
												@else
													@can('adjust-pastor-details')
														<small class="btn btn-transparent btn-sm text-danger mh-2" data-target="#stop-history-modal" data-toggle="modal" data-history="{{ $r->id }}">
															<i class="fas fa-stop"></i>
														</small>
													@endcan
													<small class="badge badge-success">CURRENT</small>
												@endif
											</td>
										</tr>
										@endforeach
										@if (sizeof($pastor->rank_history) == 0)
										<tr class="row align-items-center border-top-1 tr-py">
											<td colspan="4 text-muted">No records found.</td>
										</tr>
										@endif
									</tbody>
								</table>
							</div>
						</div>
						@stack('customer_pastor_history_content_end')
						@stack('pastor_statemrnt_content_start')
						<div class="tab-pane fade" id="pastor-statement-content" role="tabpanel" aria-labelledby="church_statements-tab">
							<div class="table-responsive custom">
								<table class="table table-flush table-hover" id="tbl-church_statements">
									<thead class="thead-light">
										<tr class="table-head-line">
											<th>No.</th>
											<th class="">{{ trans('church::general.year') }}</th>
											<th class="">{{ trans('church::general.month') }}</th>
											<th class="">{{ trans('general.amount') }}</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($statements as $item)
											<tr>
												<td style="width: 2%">{{ $loop->iteration }}</td>
												<td>{{ $item->year }}</td>
												<td>{{ $item->month }}</td>
												<td>@money($item->amount, setting('default.currency'), true)</td>
											</tr>
										@endforeach
									</tbody>
									<tfoot class="card-footer table-action">
										<tr class="row">
											<td colspan="4">
												@include('partials.admin.pagination', ['items' => $statements])
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						@stack('pastor_statemrnt_content_end')
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts_start')
<div class="modal fade show" id="stop-history-modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('church.pastor_stop_history') }}" method="post">
				@csrf
				<div class="modal-header border-bottom" style="background-color: #f8f9fe;">
					<h4>{{ trans('church::general.stop_history') }}</h4>
				</div>
				<div class="modal-body">
					<div class="form-group form-group-lg">
						<div class="alert alert-danger">
							<i class="fas fa-exclamation-triangle fa-2x pull-left float-left"></i> Are you sure that you want to update this pastors history?
						</div>
					</div>
					<div class="form-group">
						<input type="checkbox" name="delete" value="1" /> Delete this history <strong>completely</strong>.
					</div>
				</div>
				<div class="modal-footer border-top" style="background-color: #f8f9fe;">
					<span class="btn btn-white" data-dismiss="modal">Close</span>
					<button type="submit" class="btn btn-danger">UPDATE</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade show" id="pastor-history-modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{route('church.pastors.add.history')}}" method="post">
				@csrf
				<div class="modal-header border-bottom" style="background-color: #f8f9fe;">
					{{ trans('church::general.add_pastor_history') }}
				</div>
				<div class="modal-body">
					<div class="form-group form-group-lg">
						<label class="control-label">{{ trans('church::general.name') }}</label>
						<div class="input-group input-group-merge ">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fas fa-solid fa-church"></i>                                
								</span>
							</div>
							<select data-url="{{ route('church.get_church') }}/" name="church_id" placeholder="{{ trans('church::general.name') }}..." class="form-control selectize-field" data-searchfield="name" data-labelfield="name" data-valuefield="id">
							</select>
						</div>
					</div>
					<div class="form-group form-group-lg">
						<input type="hidden" name="pastor_id" value="{{$pastor->id}}">
					</div>
					@php($rank = ['Senior', 'Assistant'])
					<div class="form-group form-group-lg">
						<label class="control-label">{{ trans_choice('church::general.rank', 1) }}</label>
						<div class="input-group input-group-merge ">
							<div class="input-group-prepend">
								<span class="input-group-text">
								<i class="fas fa-solid fa-star"></i>
								</span>
							</div>
							<select name="rank" placeholder="Select {{ trans_choice('church::general.rank', 1) }}..." class="form-control selectize-field" data-searchfield="name" data-labelfield="name" data-valuefield="id">
								<option>Select Rank...</option>
								@foreach ($rank as $r)
								<option value="{{ $r }}">{{ $r }}</option>
								@endforeach
							</select>
						</div>
					</div>
					@php($positions = ['Pastor', 'Reverend' ,'Bishop'])
					<div class="form-group form-group-lg">
						<label class="control-label">{{ trans('church::general.rank') }}</label>
						<div class="input-group input-group-merge ">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fa fa-user"></i>
								</span>
							</div>
							<select name="position" placeholder="Select {{ trans('church::general.position') }}..." class="form-control selectize-field" data-searchfield="name" data-labelfield="name" data-valuefield="id">
								<option>Select Position...</option>
								@foreach ($positions as $r)
								<option value="{{ $r }}">{{ $r }}</option>
								@endforeach
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css" integrity="sha512-bkB9w//jjNUnYbUpATZQCJu2khobZXvLP5GZ8jhltg7P/dghIrTaSJ7B/zdlBUT0W/LXGZ7FfCIqNvXjWKqCYA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.bootstrap4.min.css" integrity="sha512-MMojOrCQrqLg4Iarid2YMYyZ7pzjPeXKRvhW9nZqLo6kPBBTuvNET9DBVWptAo/Q20Fy11EIHM5ig4WlIrJfQw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style type="text/css" rel="stylesheet">
	.selectize-dropdown,
	.selectize-input {
		line-height: 18px;
		padding: 13px;
	}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js" integrity="sha512-pF+DNRwavWMukUv/LyzDyDMn8U2uvqYQdJN0Zvilr6DDo/56xPDZdDoyPDYZRSL4aOKO/FGKXTpzDyQJ8je8Qw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
	var selectizerNow = function(tis) {
		var $url = $(tis).data('url');
		var $valueField = $(tis).data('valuefield');
		var $labelField = $(tis).data('labelfield');
		var $searchField = $(tis).data('searchfield');
		var $icon = $(tis).data('icon');
		var locked = $(this).attr('readonly') == 'true';

		if ($url) {
			$(tis).selectize({
				valueField: $valueField,
				labelField: $labelField,
				searchField: $searchField,
				create: false,
				render: {
					option: function(item, escape) {
						return (
							`<div class="p-2 pt-3 pb-3"><i class="far ${$icon}"></i> ${item[$labelField]}</div>`
						);
					},
				},
				load: function(query, callback) {
					if (!query.length) return callback();
					var $voucherType = $('select[name="payment_voucher_type"]').val();

					$.ajax({
						url: $url + encodeURIComponent(query),
						type: "GET",
						data: {
							'voucher_type': $voucherType
						},
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

		if (locked) {
			tis[0].selectize.lock();
		}
	}

	$(function() {
		$(this).find('.selectize-field').each(function() {
			if ($(this)[0].selectize) {
				$(this)[0].selectize.destroy();
			}
			selectizerNow(this);
		});

		$("#stop-history-modal").on("shown.bs.modal", function (e){ 
			var hID = $(e.relatedTarget).data('history');

			$(this).find('form').attr('action', "{{ route('church.pastor_stop_history') }}/"+hID);
			$(this).find('form').prop('action', "{{ route('church.pastor_stop_history') }}/"+hID);
		});

	});
</script>
<!-- <script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script> -->
@endpush