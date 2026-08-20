@extends('layouts.admin')

@section('title', $church->name)

@section('new_button')
@stack('edit_button_start')
@can('update-church-main')
	<a href="{{ route('church.edit', $church->id) }}" class="btn btn-white btn-sm">
		{{ trans('general.edit') }}
	</a>
	<span class="btn btn-warning btn-sm" data-target="#assign-subregion-modal" data-toggle="modal">
		{{ (isset($church->subregion) && isset($church->subregion->demarcation)) ? 
			trans('church::general.change_subregion') : trans('church::general.assign_subregion') }}
	</span>
@endcan
@stack('edit_button_end')
@stack('add_pastor_button_start')
@can('update-church-main')
	<span class="btn btn-default btn-sm" data-target="#pastor-history-modal" data-toggle="modal">{{ trans('church::general.add_pastor_history') }}</span>
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
				@stack('church_image_start')
				<li class="list-group-item border-0">
					<div class="p-2">
						<img src="{{ $church->photo }}" class="w-100" />	
					</div>
				</li>
				@stack('church_image_end')

				@stack('church_name_start')
				<li class="list-group-item border-0 border-top-1">
					<div class="font-weight-600">{{ trans('general.name') }}</div>
					<div><small class="long-texts" title="{{ $church->name }}">{{ $church->name }}</small></div>
				</li>
				@stack('church_name_end')

				@stack('church_code_start')
				<li class="list-group-item border-0 border-top-1">
					<div class="font-weight-600">{{ trans('church::general.code') }}</div>
					<div><small class="long-texts" title="{{ $church->code }}">{{ $church->code }}</small></div>
				</li>
				@stack('church_code_end')

				@stack('church_email_start')
				<li class="list-group-item border-0 border-top-1">
					<div class="font-weight-600">{{ trans('general.email') }}</div>
					<div><small class="long-texts" title="{{ $church->email }}">{{ $church->email }}</small></div>
				</li>
				@stack('church_email_end')

				@stack('church_region_start')
				<li class="list-group-item border-0 border-top-1">
					<div class="font-weight-600">{{ trans_choice('church::general.region', 1) }}</div>
					<div>
						@if(isset($region) && isset($region->name))
							<a class="long-texts" href="{{ route('demarcations.show', [$region->type, $region->id]) }}">{{ $region->name }}</a>
						@else
							<span class="badge badge-warning badge-sm">Not Set</span>
						@endif
					</div>
				</li>
				@stack('church_region_end')

				@stack('church_subregion_start')
				<li class="list-group-item border-0 border-top-1">
					<div class="font-weight-600">{{ trans_choice('church::general.subregion', 1) }}</div>
					<div>
						@if(isset($subRegion) && isset($subRegion->id))
							<a class="long-texts" href="{{ route('demarcations.show', [$subRegion->type, $subRegion->id]) }}">
								{{ $subRegion->name }}
							</a>
						@else
							<span class="badge badge-warning badge-sm">Not Set</span>
						@endif
					</div>
				</li>
				@stack('church_subregion_end')
				@stack('church_pastors_start')
				<li class="list-group-item border-0 border-top-1">
					<div class="font-weight-600">{{ trans_choice('church::general.pastor', 2) }}</div>
					<div>
						@if(!empty($church->pastors))
							@foreach ($church->pastors as $pastor)
								<a class="long-texts text-sm {{ $loop->index > 1 ? ' border-top-1' : '' }}" href="{{ route('church.pastors.show', $pastor->pastor->id) }}">
									<small class="badge bg-dark text-white badge-pill">{{ $pastor->rank }}</small> <small class="badge bg-light text-dark badge-pill">{{ $pastor->position }}</small> <small class="text-muted">{{ $pastor->pastor->contact->name }}</small>
								</a>
							@endforeach
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
							@stack('customer_church_history_tab_start')
							<li class="nav-item">
								<a class="nav-link mb-sm-3 mb-md-0 active" id="church_history-tab" data-toggle="tab" href="#church_history-content" role="tab" aria-controls="church_history-content" aria-selected="true">
									{{ trans_choice('church::general.church_history', 2) }}
								</a>
							</li>
							@stack('customer_church_history_tab_end')
				
							@stack('customer_pastor_history_tab_start')
							<li class="nav-item">
								<a class="nav-link mb-sm-3 mb-md-0" id="pastor_history-tab" data-toggle="tab" href="#pastor_history-content" role="tab" aria-controls="pastor_history-content" aria-selected="false">
									{{ trans_choice('church::general.pastor_history', 2) }}
								</a>
							</li>
							@stack('customer_pastor_history_tab_end')
				
							@stack('customer_church_statements_tab_start')
							<li class="nav-item">
								<a class="nav-link mb-sm-3 mb-md-0" id="church_statements-tab" data-toggle="tab" href="#church_statements-content" role="tab" aria-controls="church_statements-content" aria-selected="false">
									{{ trans_choice('church::general.church_statement', 2) }}
								</a>
							</li>
							@stack('customer_church_statements_tab_end')
						</ul>
					</div>				
					<div class="card">
						<div class="tab-content" id="cutomer-tab-content">
							@stack('customer_pastor_history_content_start')
							<div class="tab-pane fade" id="pastor_history-content" role="tabpanel" aria-labelledby="invoices-tab">
								<div class="table-responsive custom">
									<table class="table table-flush table-hover" id="tbl-pastor_history">
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
											@foreach($church->pastor_history as $r)
												<tr>
													<td>{{ isset($r->pastor->contact) ? $r->pastor->contact->name : 'Removed' }}</td>
													<td>{{ $r->rank }}</td>
													<td>{{ $r->position }}</td>
													<td>{{ $r->created_at }}</td>
													<td>
														@if(trim($r->deleted_at) != "") 
															{{ $r->deleted_at }}
														@else 
															@can('update-church-main')
																<small class="btn btn-transparent btn-sm text-danger mh-2" data-target="#stop-history-modal" data-toggle="modal" data-history="{{ $r->id }}">
																	<i class="fas fa-stop"></i>
																</small>
															@endcan
															<small class="badge badge-success">CURRENT</small>
														@endif
													</td>
												</tr>
											@endforeach
											@if (empty($church->pastor_history))
												<tr class="row align-items-center border-top-1 tr-py">
													<td colspan="5 text-muted">No records found.</td>
												</tr>
											@endif
										</tbody>
									</table>
								</div>
							</div>
							@stack('customer_pastor_history_content_end')
							@stack('customer_church_history_content_start')
							<div class="tab-pane fade show active" id="church_history-content" role="tabpanel" aria-labelledby="church_history-tab">
								<div class="table-responsive custom">
									<table class="table table-flush table-hover" id="tbl-church_history">
										<thead class="thead-light">
											<tr class="table-head-line">
												<th class="">{{ trans_choice('general.numbers', 1) }}</th>
												<th class="">{{ trans_choice('church::general.sub_region', 1) }}</th>
												<th class="">{{ trans_choice('church::general.start', 1) }}</th>
												<th class="">{{ trans_choice('church::general.end', 1) }}</th>
											</tr>
										</thead>
				
										<tbody>
											@foreach ($church->history as $h)
											<tr>
												<td>{{ $loop->iteration }}</td>
												<td>{{ $h->demarcation ? $h->demarcation->name : '' }}</td>
												<td>{{ $h->created_at }}</td>
												<td>
													@if(trim($h->deleted_at) != "") 
														{{ $h->deleted_at }}
													@else 
														@can('update-church-main')
															<small class="btn btn-transparent btn-sm text-danger mh-2" data-target="#stop-dem-history-modal" data-toggle="modal" data-history="{{ $h->id }}">
																<i class="fas fa-stop"></i>
															</small>
														@endcan
														<small class="badge badge-success">CURRENT</small>
													@endif
												</td>
											</tr>
											@endforeach
											@if($church->history->count() == 0)
												<tr class="row align-items-center border-top-1 tr-py">
													<td colspan="5 text-muted">No records found.</td>
												</tr>
											@endif
										</tbody>
									</table>
								</div>
							</div>
							@stack('customer_church_history_content_end')
							@stack('customer_church_statements_content_start')
							<div class="tab-pane fade" id="church_statements-content" role="tabpanel" aria-labelledby="church_statements-tab">
								<div class="table-responsive custom">
									<table class="table table-flush table-hover" id="tbl-church_statements">
										<thead class="thead-light">
											<tr class="table-head-line">
												<th class="">{{ trans_choice('general.numbers', 1) }}</th>
												<th class="">{{ trans_choice('general.month', 1) }}</th>
												<th class="">{{ trans_choice('general.year', 1) }}
													<span class="btn btn-sm btn-transparent text-info" data-target="#year-changer-modal" data-toggle="modal">
														<i class="fas fa-filter"></i>
													</span>
												</th>
												<th class="">{{ trans_choice('general.amount', 1) }}</th>
												<th class="">{{ trans('payment-reception::general.payment_by') }}</th>
												<th class="">{{ trans('general.date') }}</th>
												<th class="">
													<span class="btn btn-sm btn-transparent" id="download-statements">
														<i class="fas fa-download"></i>
													</span>
												</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($statements as $money)
												<tr>
													<td>{{ $loop->iteration }}</td>
													<td>{{ $money->month }}</td>
													<td>{{ $money->year }}</td>
													<td>@money($money->amount, 'KES', true)</td>
													<td>{{ $money->payer_name }}</td>
													<td>{{ $money->created_at }}</td>
													<td><a href="{{ route('payment-reception.view-tithe', $money->id) }}"><i class="fas fa-eye"></i> View</a></td>
												</tr>
											@endforeach
											@if($statements->count() == 0)
												<tr class="row align-items-center border-top-1 tr-py">
													<td colspan="5 text-muted">No records found.</td>
												</tr>
											@endif
										</tbody>
									</table>
								</div>
							</div>
							@stack('customer_church_statements_content_end')
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
<div class="modal fade show" id="stop-history-modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('church.pastor_stop_history') }}" method="post">
				@csrf
				<div class="modal-header border-bottom" style="background-color: #f8f9fe;">
					{{ trans('church::general.stop_history') }}
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
<div class="modal fade show" id="year-changer-modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="" method="get">
				<div class="modal-header border-bottom" style="background-color: #f8f9fe;">
					{{ trans('Filter Statements By Year') }}
				</div>
				<div class="modal-body">
					<div class="form-group form-group-lg">
						<label class="control-label">Year</label>
						<select class="form-control" name="year">
							<option value="all">All Years</option>
								@for($y=2022; $y<=date('Y'); $y++)
							<option value="{{ $y }}">{{ $y }}</option>
							@endfor
						</select>
					</div>
				</div>
				<div class="modal-footer border-top" style="background-color: #f8f9fe;">
					<span class="btn btn-white" data-dismiss="modal">Close</span>
					<button type="submit" class="btn btn-danger">Filter</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade show" id="stop-dem-history-modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('church.dem_stop_history') }}" method="post">
				@csrf
				<div class="modal-header border-bottom" style="background-color: #f8f9fe;">
					Remove Church From Sub Region
				</div>
				<div class="modal-body">
					<div class="form-group form-group-lg">
						<div class="alert alert-danger">
							<i class="fas fa-exclamation-triangle fa-2x pull-left float-left"></i> Are you sure that you want to update this church`s history?
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
<div class="modal fade show" id="assign-subregion-modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{route('church.assign_subregion', $church->id)}}" method="post">
				@csrf
				<div class="modal-header border-bottom" style="background-color: #f8f9fe;">
					{{ trans('church::general.assign_subregion') }}
				</div>
				<div class="modal-body">
					<div class="form-group form-group-lg">
						<label class="control-label">{{ trans_choice('church::general.subregion', 1) }}</label>
						<div class="input-group input-group-merge ">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fa fa-user"></i>
								</span>
							</div>
							<select data-url="{{ route('church.fetch_demarcations', $church->id) }}/" name="subregion_id" 
								placeholder="{{ trans_choice('church::general.subregion', 1) }}..."
								class="form-control selectize-field" data-searchfield="name" data-labelfield="name" data-valuefield="id">
								@foreach ($subregions as $sub)
									<option value="{{ $sub->id }}">{{ $sub->name }}</option>
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
<div class="modal fade show" id="pastor-history-modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('church.add_to_church', $church->id) }}" method="post">
				@csrf
				<div class="modal-header border-bottom" style="background-color: #f8f9fe;">
					{{ trans('church::general.add_pastor_history') }}
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
					@php($rank = ['Senior', 'Assistant'])
					<div class="form-group form-group-lg">
						<label class="control-label">{{ trans_choice('church::general.rank', 1) }}</label>
						<div class="input-group input-group-merge ">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fa fa-user"></i>
								</span>
							</div>
							<select name="rank" placeholder="Select {{ trans_choice('church::general.rank', 1) }}..."
								class="form-control selectize-field" data-searchfield="name" data-labelfield="name" data-valuefield="id">
								<option>Select Rank...</option>
								@foreach ($rank as $r)
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

	function convertTableToCSV(tableId, fileName) {
		const table = document.getElementById(tableId);
		const rows = Array.from(table.querySelectorAll('tr'));

		// Function to clean up the cell data and escape special characters
		const cleanCellData = (data) => {
			const cleanedData = data.replace(/"/g, '""'); // Escape double quotes
			return `"${cleanedData}"`; // Enclose cell data in double quotes
		};

		// Convert each row to CSV format
		const csvRows = rows.map((row) => {
			const cells = Array.from(row.querySelectorAll('th, td'));
			const csvRow = cells.map((cell) => cleanCellData(cell.textContent)).join(',');
			return csvRow;
		});

		// Join rows with line breaks to form the final CSV content
		const csvContent = csvRows.join('\n');

		// Create a download link
		const downloadLink = document.createElement('a');
		downloadLink.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csvContent));
		downloadLink.setAttribute('download', fileName);

		// Append the link to the document body and trigger the download
		document.body.appendChild(downloadLink);
		downloadLink.click();
		document.body.removeChild(downloadLink);
	}

	$(function(){
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

		$("#download-statements").on('click', function(){
			const tableId = 'tbl-church_statements'; // Replace with the actual ID of your HTML table
			const filename = '{{ $church->name }}-statements.csv';
			const csvContent = convertTableToCSV(tableId, filename);
			console.log(csvContent)
		});

		$("#stop-dem-history-modal").on("shown.bs.modal", function (e){ 
			var hID = $(e.relatedTarget).data('history');

			$(this).find('form').attr('action', "{{ route('church.dem_stop_history') }}/"+hID);
			$(this).find('form').prop('action', "{{ route('church.dem_stop_history') }}/"+hID);
		});
	});
</script>
	{{-- <script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script> --}}
@endpush
