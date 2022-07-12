@extends('layouts.admin')

@php($isSubRegion = in_array($type, ["Sub Region", "Sub-Region", 'subregion']))

@section('title', $demarcation->name)

@section('new_button')
@stack('edit_button_start')
@can('create-demarcations-main')
	<a href="{{ route('demarcations.edit', [$type, $demarcation->id]) }}" class="btn btn-white btn-sm">
		{{ trans('general.edit') }}
	</a>
	@if (!$isSubRegion)
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

				@if($isSubRegion)
					@stack('demarcations_region_start')
					<li class="list-group-item border-0 border-top-1">
						<div class="font-weight-600">{{ trans_choice('church::general.region', 1) }}</div>
						<div>
							@if(isset($demarcation->parent) && isset($demarcation->parent->parent))
								<a class="long-texts" href="{{ route('demarcations.show', [$demarcation->parent->parent->type, $demarcation->parent->parent->id]) }}">{{ $demarcation->parent->parent->name }}</a>
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
			<div class="row mb--3">
				@stack('customer_paid_card_start')
				<div class="col-md-4">
					<div class="card bg-gradient-success border-0">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="text-uppercase text-muted mb-0 text-white">{{ trans('demarcations::general.received') }}</h5>
									<div class="dropdown-divider"></div>
									<span class="h2 font-weight-bold mb-0 text-white">@money($demarcation->amounts('received'), setting('default.currency'), true)</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				@stack('customer_paid_card_end')
			
				@stack('customer_open_card_start')
				<div class="col-md-4">
					<div class="card bg-gradient-warning border-0">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="text-uppercase text-muted mb-0 text-white">{{ trans('demarcations::general.paid') }}</h5>
									<div class="dropdown-divider"></div>
									<span class="h2 font-weight-bold mb-0 text-white">@money($demarcation->amounts('paid'), setting('default.currency'), true)</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				@stack('customer_open_card_end')

				@stack('customer_overdue_card_start')
				<div class="col-md-4">
					<div class="card bg-gradient-danger border-0">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h5 class="text-uppercase text-muted mb-0 text-white">{{ trans('demarcations::general.unpaid') }}</h5>
									<div class="dropdown-divider"></div>
									<span class="h2 font-weight-bold mb-0 text-white">@money($demarcation->amounts('received')-$demarcation->amounts('paid'), setting('default.currency'), true)</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				@stack('customer_overdue_card_end')
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="nav-wrapper">
						<ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
							@stack('customer_subregion_tab_start')
							<li class="nav-item">
								<a class="nav-link mb-sm-3 mb-md-0 active" id="subregion-tab" data-toggle="tab" href="#subregion-content" role="tab" aria-controls="subregion-content" aria-selected="true">
									{{ $isSubRegion ? trans_choice('church::general.title', 2) : trans_choice('demarcations::general.subregion', 2) }}
								</a>
							</li>
							@stack('customer_subregion_tab_end')

							@if($isSubRegion)
								@stack('customer_subregion_history_tab_start')
								<li class="nav-item">
									<a class="nav-link mb-sm-3 mb-md-0" id="subregion_history-tab" data-toggle="tab" href="#subregion_history-content" role="tab" aria-controls="subregion_history-content" aria-selected="true">
										{{ $isSubRegion ? trans('demarcations::general.subregion_history') : trans('demarcations::general.region_history') }}
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
				
							@stack('demarcation_payment_start')
							<li class="nav-item">
								<a class="nav-link mb-sm-3 mb-md-0" id="payments-tab" data-toggle="tab" href="#payments-content" role="tab" aria-controls="payments-made-content" aria-selected="false">
									{{ trans_choice('demarcations::general.payment', 2) }}
								</a>
							</li>
							@stack('demarcation_payment_end')
				
							@stack('payments_made_start')
							<li class="nav-item">
								<a class="nav-link mb-sm-3 mb-md-0" id="make-payments-tab" data-toggle="tab" href="#make-payments-content" role="tab" aria-controls="payments-made-content" aria-selected="false">
									{{ trans('demarcations::general.make_payment') }}
								</a>
							</li>
							@stack('payments_made_end')
						</ul>
					</div>		
					<div class="row-fluid mb-2 d-none" id="make-payments-holder" style="margin-right: 0px !important; padding-right: 0px!important">
						<div class="col-md-4"></div>
						<div class="col-md-4"></div>
						<div class="col-md-4" style="margin-right: 0px !important; padding-right: 0px!important">
							<span class="btn btn-danger btn-sm" data-toggle="modal" data-target="#mark-bill-paid-modal">{{ 'Pay Pending Payments' }}</span>
						</div>
					</div>		
					<div class="card">
						<div class="tab-content" id="cutomer-tab-content">
							@stack('demarcation_payment_made_start')
							<div class="tab-pane fade" id="payments-content" role="tabpanel" aria-labelledby="payments-content-tab">
								<div class="table-responsive custom">
									<table class="table table-flush table-hover" id="tbl-make-payment-bill">
										<thead class="thead-light">
											<tr class="table-head-line">
												<th><input type="checkbox" class="toggle-mark-bill-paid" /></th>
												<th>Type</th>
												<th class="">{{ trans_choice('demarcations::general.cheque', 1) }}</th>
												<th class="">{{ trans('general.amount') }}</th>
												<th>Status</th>
												<th class="">{{ trans('general.month') }}</th>
												<th class="">{{ trans('general.year') }}</th>
											</tr>
										</thead>
										<tbody>
											@foreach($demarcation->bills as $bill)
												@php($status = $bill->status == 'paid' ? 'PAID' : 'UNPAID')
												<tr>
													<td style="vertical-align: middle">
														@if($status == "UNPAID")
															<div class="">
																<input value="{{ $bill->id }}" data-bill="{{ $bill }}" type="checkbox" class="mark-bill-paid" />
															</div>
														@else
														<input data-value="null" type="checkbox" class="custom-control-input" disabled>
														@endif
													</td>
													<td>{{ $bill->contact_name }}</td>
													<td>{{ trim($bill->reference) == '' ? '-' : $bill->reference }}</td>
													<td>@money($bill->amount, setting('default.currency'), true)</td>
													<td><small class="badge badge-pill text-white bg-{{ $status == 'PAID' ? 'green' : 'red' }}">{{ $status }}</small></td>
													<td>{{ \Carbon\Carbon::parse($bill->created_at)->format('F') }}</td>
													<td>{{ \Carbon\Carbon::parse($bill->created_at)->format('Y') }}</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
							@stack('demarcation_payment_made_end')
							@stack('demarcation_payment_made_start')
							<div class="tab-pane fade" id="make-payments-content" role="tabpanel" aria-labelledby="make-payments-content-tab">
								<form class="table-responsive custom" method="POST" action="{{ route('demarcations.make-payments', $demarcation->id) }}">
									@csrf
									@php($AMOUNTTOPAY=0)
									{{-- Start of Accordion --}}
									<div class="accordion">
										<div class="card" style="margin-bottom: 5px !important">
											<div id="accordion-histories-header" data-toggle="collapse" data-target="#accordion-histories-body" aria-expanded="true" aria-controls="accordion-histories-body" class="card-header">
												<h4 class="mb-0">
													<small class="badge bg-light badge-pill text-small">{{ $demarcation->type }}</small>
													{{ $demarcation->name }}
													<div style="float: right;	margin-right: 50px;" class="pull-right badge badge-success badge-pill">@money($demarcation->amounts('received') - $demarcation->amounts('paid'), setting('default.currency'), true)</div>
												</h4>
											</div> 
											<div id="accordion-histories-body" aria-labelledby="accordion-histories-header" class="hide collapse show" style="">
												<div class="table-responsive">
													<table class="table table-flush table-hover">
														<thead class="thead-light">
															<tr class="table-head-line">
																<th colspan="1%">{{ Form::bulkActionAllGroup() }}</th>
																<th class="">{{ trans_choice('church::general.title', 1) }}</th>
																<th class="">{{ trans('general.amount') }}</th>
																<th class="">{{ trans('general.month') }}</th>
																<th class="">{{ trans('general.year') }}</th>
															</tr>
														</thead> 
														<tbody>
															@php($HTOTAL = 0)
															@foreach($demarcation->pending_payments('region') as $pd)
																@php($AMOUNTTOPAY+=$pd->amount ?? 0)
																@php($HTOTAL+=$pd->amount ?? 0)
																<tr class="align-items-center border-top-1 tr-py">
																	<td>
																		<div class="custom-control custom-checkbox">
																			<input checked="true" data-value="{{ $pd->amount }}" type="checkbox" class="custom-control-input amount-to-pay-cbx" id="bulk-action-dem-{{ $loop->iteration }}" data-bulk-action="{{ $loop->iteration }}" :value="{{ $pd->pd_ids }}" v-model="bulk_action.selected" v-on:change="onSelect" value="{{ $pd->pd_ids }}" name="items[region][{{ $loop->iteration }}]">
																			<label class="custom-control-label" for="bulk-action-1648024450"></label>
																		</div>
																	</td>
																	<td>{{ $pd->name }}</td>
																	<td>@money($pd->amount, setting('default.currency'), true)</td>
																	<td>{{ ucfirst($pd->month) }}</td>
																	<td>{{ $pd->year }}</td>
																</tr>
															@endforeach
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
									@if (!$isSubRegion)
										{{-- End of Accordion --}}
										@foreach ($demarcation->children as $item)
											<div class="accordion">
												<div class="card" style="margin-bottom: 5px !important">
													<div id="accordion-histories-header-{{ $loop->iteration }}" data-toggle="collapse" data-target="#accordion-histories-body-{{ $loop->iteration }}" aria-expanded="false" aria-controls="accordion-histories-body-{{ $loop->iteration }}" class="card-header collapsed">
														<h4 class="mb-0" style="width: 100%">
															<small class="badge bg-light badge-pill text-small">{{ $item->demarcation->type }}</small>
															{{ $item->demarcation->name }}
															<div style="float: right;	margin-right: 50px;" class="pull-right badge badge-success badge-pill">@money($item->demarcation->amounts('received') - $item->demarcation->amounts('paid'), setting('default.currency'), true)</div>
														</h4>
													</div> 
													<div id="accordion-histories-body-{{ $loop->iteration }}" aria-labelledby="accordion-histories-header-{{ $loop->iteration }}" class="hide collapse" style="">
														<div class="table-responsive">
															<table class="table table-flush table-hover">
																<thead class="thead-light">
																	<tr class="table-head-line">
																		<th colspan="1%">{{ Form::bulkActionAllGroup(['id'=>'maize']) }}</th>
																		<th class="">{{ trans_choice('church::general.title', 1) }}</th>
																		<th class="">{{ trans('general.amount') }}</th>
																		<th class="">{{ trans('general.month') }}</th>
																		<th class="">{{ trans('general.year') }}</th>
																	</tr>
																</thead> 
																<tbody>
																	@php($HTOTAL = 0)
																	@foreach($item->demarcation->pending_payments('sub_region') as $pd)
																		@php($AMOUNTTOPAY+=$pd->amount ?? 0)
																		@php($HTOTAL+=$pd->amount ?? 0)
																		<tr class="align-items-center border-top-1 tr-py">
																			<td>
																				<div class="custom-control custom-checkbox">
																					<input checked="true" data-value="{{ $pd->amount }}" type="checkbox" class="custom-control-input amount-to-pay-cbx" id="bulk-action-sub_dem-{{ $loop->iteration }}" data-bulk-action="{{ $loop->iteration }}" :value="{{ $pd->pd_ids }}" v-model="bulk_action.selected" v-on:change="onSelect" value="{{ $pd->pd_ids }}" name="items[sub_region][{{ $item->demarcation->id }}]">
																					<label class="custom-control-label" for="bulk-action-sub_dem-{{ $loop->iteration }}"></label>
																				</div>
																			</td>
																			<td>{{ $pd->name }}</td>
																			<td>@money($pd->amount, setting('default.currency'), true)</td>
																			<td>{{ ucfirst($pd->month) }}</td>
																			<td>{{ $pd->year }}</td>
																		</tr>
																	@endforeach
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										@endforeach
									@endif
									@if (!$isSubRegion)
										<br>
										<div style="width: 100%; height: 11px; border-bottom: 1px solid #ddd; text-align: center">
											<span style="font-size: 13px; background-color: #ffffff; padding: 0 10px;">
												Other Payments <!--Padding is optional-->
											</span>
										</div>
										<br><br>
										@foreach ($invoice_items as $rowD)
											@if (!in_array($rowD['code'], ['dc_secretariate', 'region', 'sub_region']))
												<div class="accordion">
													<div class="card" style="margin-bottom: 5px !important">
														<div id="accordion-other-payments-header-{{ $loop->iteration }}" data-toggle="collapse" data-target="#accordion-other-payments-body-{{ $loop->iteration }}" aria-expanded="false" aria-controls="accordion-other-payments-body-{{ $loop->iteration }}" class="card-header collapsed">
															<h4 class="mb-0" style="width: 100%">
																{{ ucwords(implode(' ', (explode('_', $rowD['code'])))) }}
																<div style="float: right;	margin-right: 50px;" class="pull-right badge badge-success badge-pill">@money($demarcation->other_pending_payments($rowD['code'])->amount ?? 0, setting('default.currency'), true)</div>
															</h4>
														</div> 
														<div id="accordion-other-payments-body-{{ $loop->iteration }}" aria-labelledby="accordion-other-payments-header-{{ $loop->iteration }}" class="hide collapse" style="">
															<div class="table-responsive">
																<table class="table table-flush table-hover">
																	<thead class="thead-light">
																		<tr class="table-head-line">
																			<th colspan="1%">{{ Form::bulkActionAllGroup() }}</th>
																			@if ($rowD['code'] == 'pastors_fund')
																				<th class="">{{ trans_choice('church::general.pastor', 1) }}</th>																					
																			@endif
																			<th class="">{{ trans_choice('church::general.title', 1) }}</th>
																			<th class="">{{ trans('general.amount') }}</th>
																			<th class="">{{ trans('general.month') }}</th>
																			<th class="">{{ trans('general.year') }}</th>
																		</tr>
																	</thead> 
																	<tbody>
																		@php($HTOTAL = 0)
																		@foreach(($rowD['code'] == 'pastors_fund' ? $demarcation->pastors_fund('pending') : $demarcation->pending_payments($rowD['code'])) as $pd)
																			@php($AMOUNTTOPAY+=$pd->amount ?? 0)
																			@php($HTOTAL+=$pd->amount ?? 0)
																			<tr class="align-items-center border-top-1 tr-py">
																				<td>
																					<div class="custom-control custom-checkbox">
																						<input checked="true" data-value="{{ $pd->amount }}" type="checkbox" class="custom-control-input amount-to-pay-cbx" id="bulk-action-{{ $rowD['code'] }}-{{ $loop->iteration }}" data-bulk-action="{{ $loop->iteration }}" :value="{{ $pd->pd_ids }}" v-model="bulk_action.selected" v-on:change="onSelect" value="{{ $pd->pd_ids }}" name="items[{{$rowD['code']}}][{{ $rowD['code'] != 'pastors_fund' ? $demarcation->id : $pd->pastor_id }}]">
																						<label class="custom-control-label" for="bulk-action-{{ $rowD['code'] }}-{{ $loop->iteration }}"></label>
																					</div>
																				</td>
																				@if ($rowD['code'] == 'pastors_fund')
																					<td>{{ $pd->pastor }}</td>
																				@endif
																				<td>{{ $pd->name }}</td>
																				<td>@money($pd->amount, setting('default.currency'), true)</td>
																				<td>{{ ucfirst($pd->month) }}</td>
																				<td>{{ $pd->year }}</td>
																			</tr>
																		@endforeach
																	</tbody>
																	<tfoot>
																		<tr>
																			<th colspan="3" class="text-right"> TOTAL</th>
																			<th colspan="3">@money($HTOTAL, setting('default.currency'), true)</th>
																		</tr>
																	</tfoot>
																</table>
															</div>
														</div>
													</div>
												</div>
											@endif
										@endforeach	
									@endif
									<div class="row" style="margin: 0px !important">
										<div class="col-md-4"></div>
										<div class="col-md-4"></div>
										<div class="col-md-4 p-2">
											<button class="btn btn-block btn-danger">MAKE PAYMENT <span class="badge badge-pill bg-red amount-to-pay">@money($AMOUNTTOPAY, setting('default.currency'), true)</span></button>
										</div>
									</div>
								</form>
							</div>
							@stack('demarcation_payment_made_end')
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
												<th class="">{{ !$isSubRegion ? trans_choice('demarcations::general.overseer', 1) : trans('church::general.senior') }}</th>
												<th class="">{{ !$isSubRegion ? trans_choice('demarcations::general.church', 2) : trans_choice('church::general.pastor', 2) }}</th>
											</tr>
										</thead>
										<tbody>
												@foreach ($demarcation->children as $item)
													<tr class="border-top-1 tr-py">
														<td>
															@if (!$isSubRegion)
																<a href="{{ route('demarcations.show', ['subregion', $item->demarcation_id]) }}">{{ $item->demarcation->name }}</a>
															@else
																<a href="{{ route('church.show', [$item->church_id]) }}">{{ $item->demarcation->name }}</a>																	
															@endif
														</td>
														<td>
															@if (!$isSubRegion)
																<a href="{{ route('demarcations.show', ['subregion', $item->demarcation_id]) }}">{{ $item->demarcation->code }}</a>
															@else
																<a href="{{ route('church.show', [$item->church_id]) }}">{{ $item->demarcation->code }}</a>																	
															@endif
														</td>
														@if(isset($item->demarcation->overseer) && isset($item->demarcation->overseer->rank))
															@if(!$isSubRegion)
																<td>{{ isset($item->demarcation->overseer) ? $item->demarcation->overseer->rank->position.' '.$item->demarcation->overseer->pastor->contact->name : 'Not Set' }}</td>
															@else
																<td>{{ isset($item->church->senior_pastor->rank) ? $item->church->senior_pastor->rank->position.' '.$item->church->senior_pastor->pastor->contact->name : 'Not Set' }}</td>
															@endif
														@endif
														
														<td>{{ !$isSubRegion ? $item->demarcation->children->count() : $item->church->pastors->count() }}</td>
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
							@if($isSubRegion)
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
					<div class="form-group form-group-lg">
						<label class="control-label">{{ trans('general.name') }}</label>
						<div class="input-group input-group-merge ">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fa fa-font"></i>
								</span>
							</div>
							<input type="text" class="form-control" name="name" placeholder="{{ trans('general.name') }}..." />
						</div>
					</div>
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
<div class="modal fade show" id="mark-bill-paid-modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{route('demarcations.mark-bill-paid')}}" method="post">
				@csrf
				<div class="modal-header border-bottom" style="background-color: #f8f9fe;">
					{{ 'PAY PENDING PAYMENTS' }}
				</div>
				<div class="modal-body">
					<div class="form-group">
							<label class="form-label">Cheque Number</label>
							<input type="text" required class="form-control" required name="cheque_number" placeholder="Cheque Number..." />
						</div>
						<div class="form-group">
							<label class="form-label">Date Paid</label>
							<input type="date" required class="form-control" name="date_paid" placeholder="Date Paid..." />
						</div>
					<div class="alert alert-danger">
						Are you sure you want to mark these payments as paid?
					</div>
					<div class="row" id="bills-holder"></div>
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

	function numberWithCommas(x) {
			return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

	$(function(){
		$(this).find('.selectize-field').each(function() {
			if ($(this)[0].selectize) {
				$(this)[0].selectize.destroy();
			}
			selectizerNow(this);
		});

		$('#mark-bill-paid-modal').on('shown.bs.modal', function(){
			var checkbx = $('.mark-bill-paid:checked');
			var billIDS = [];
			checkbx.each(function(){
				var $BILL = $(this).data('bill');
				var $col = $(`<div class="col-md-6 p-1" style="display: flex; padding-left: 0px">
					<small><input checked="true" type="checkbox" value="${ $BILL.id }" name="bills[${ $BILL.id }]"> &nbsp;&nbsp; ${ $BILL.contact_name } (<small>KSH${ numberWithCommas($BILL.amount.toFixed(2)) }</small>)</small>
				</div>`);
				$('#bills-holder').append($col);
			});
		});

		$('.toggle-mark-bill-paid').on('change', function(){
			var isChecked = $(this).is(':checked');
			var parentTable = $(this).parents('table');
			parentTable.find('.mark-bill-paid').each(function(){
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
				$(this).trigger('change')
			});
		});

		$('.mark-bill-paid').on('change', function(){
			var length = $('.mark-bill-paid:checked').length;
			$('#make-payments-holder').removeClass('d-none');
			if(length > 0){
				$('#make-payments-holder').slideDown(300);
			}
			else{
				$('#make-payments-holder').slideUp(300);
			}
		});

		var checkChecked = function(){
			var TOPAY = 0;
			$('.amount-to-pay-cbx').each(function(){
				var AMOUNT = $(this).data('value');
				if($(this).is(':checked')){
					TOPAY+=AMOUNT;
				}
				$('.amount-to-pay').text('KSH'+numberWithCommas(TOPAY.toFixed(2)));
			});
		}

		$('.table-head-line').find('[type="checkbox"]').on('change', function(){
			var table = $(this).parents('table');
			var vbody = table.find('tbody');
			var isChecked = $(this).is(':checked');
			var TOPAY = 0;
			vbody.find('.amount-to-pay-cbx').each(function(){
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
	});
</script>
	{{-- <script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script> --}}
@endpush
