<div>
	<!-- Main page with lists -->
	@if ($page1 === null && $page2 === null)
		<div class="card">
			<div class="card-header">
				<h2 class="card-title">Make Payments</h2>
			</div>
			<div class="card-body">
				<ul class="list-group list-group-flush">
					@foreach ($lists as $k => $v)
						<li class="list-group-item d-flex justify-content-between align-items-center"
							style="cursor: pointer" wire:click="showType('{{ $v['code'] }}')">
							<h5>{{ $v['name'] }}</h5>
							<span class="righer">
								<span class="badge badge-dark badge-pill">@money($v['amount'] ?? 0, setting('default.currency'), true)</span>&nbsp;&nbsp;&nbsp;&nbsp;
								<i class="fas fa-angle-right text-muted"></i>
							</span>
						</li>
					@endforeach
				</ul>
			</div>
		</div>
	@endif

	@if ($page1 !== null && $page2 === null)
		<div class="card">
			<div class="card-header">
				<div class="d-flex justify-content-between">
					<div>
						<h2 class="card-title">
						<span class="btn btn-transparent btn-sm" wire:click="backToLists()"><i class="fas fa-arrow-left"></i></span>
							Make Payments <small><i class="fas fa-caret-right"></i> {{ $pageTitle }}</small>
						</h2>
					</div>
					@if ($hasPayAll)
						<input type="text" class="form-control form-control-sm" placeholder="Cheque Number..." wire:model='chequeNumbers.All' style="width: 250px" />		
					@endif
					<div class="pt-1">
						<span class="badge badge-dark badge-pill">@money($grandTotal, setting('default.currency'), true)</span>&nbsp;&nbsp;&nbsp;&nbsp;
						<span class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addPaymentModal">
						<i class="fas fa-hand-holding-usd"></i> Pay</span>
					</div>
				</div>
			</div>

			@if ($showErrors)
				<div class="card-body">
					<div class="text-danger p-1 {{ isset($errors_there['pay_date']) && $errors_there['pay_date'] ? '' : 'd-none' }}">*Missing date details.</div> 
					<div class="text-danger p-1 {{ isset($errors_there['cheques']) && $errors_there['cheques'] ? '' : 'd-none' }}">*Missing Cheque details.</div> 
				</div>
			@endif
		

			<div class="card-body" style="background-color: #f3f3f3; padding-left: 0px !important; padding-right: 0px !important">
				<ul class="list-group list-group-flush p-2 mb-1" style="background-color: #fdfdfd; border-radius: 1px">
					@foreach ($breakdown as $reg=>$items)
						@php($regName = $reg.'-region')
						<div class="region mv-1 accord">
							<div class="title p-2 border-bottom d-flex justify-content-between" style="cursor: pointer" wire:click="toggleAccordion('{{ $regName }}')">
								<div style="width: 300px"><h4>{{ $reg }}</h4></div>
								@if ($hasPayReg)
									<input type="text" class="form-control form-control-sm" placeholder="Cheque Number..." wire:model='chequeNumbers.{{ $reg }}' style="width: 250px" />								
								@endif
								<div class="pt-1">
									<span class="badge badge-dark badge-pill">@money($items['total'], setting('default.currency'), true)</span>&nbsp;&nbsp;&nbsp;&nbsp;
									<i class="fas fa-angle-{{ $accordion[$regName] ? 'down' : 'right' }} text-muted"></i>
								</div>
							</div>
							<div class="body border-top" style="display: {{ $accordion[$regName] ? 'block' : 'none' }}">
								@foreach ($items['items'] as $sub=>$mms)
									@php($subRegName = $sub.'-subregion')
									<div class="mv-1 sub-region accord">
										<div class="title p-2 border-bottom d-flex justify-content-between" style="cursor: pointer" wire:click="toggleAccordion('{{ $subRegName }}')">
											<div style="width: 300px"><h5>{{ $sub }}</h5></div>
											@if ($hasPaySub)
												<input type="text" class="form-control form-control-sm" placeholder="Cheque Number..." wire:model='chequeNumbers.{{ $reg.$sub }}' style="width: 250px" />
											@endif
											<div class="pt-1">
												<span class="badge badge-default badge-pill">@money($mms['total'], setting('default.currency'), true)</span>&nbsp;&nbsp;&nbsp;&nbsp;
												<i class="fas fa-angle-{{ $accordion[$subRegName] ? 'down' : 'right' }} text-muted"></i>
											</div>
										</div>
										<div class="body table-responsived" style="display:  {{ $accordion[$subRegName] ? 'block' : 'none' }}">
											<table class="table table-flush table-hover">
												<thead class="thead-light">
													<tr class="table-head-line">
														<th>
															<input type="checkbox" id="toggle-all" wire:click='toggleSelected("{{ $reg.$sub }}", false, "{{ $toggleAll[$reg.$sub] ? "true" : "false" }}")' {{ $toggleAll[$reg.$sub] ? 'checked' : '' }} />
														</th>
														@if (strtolower($pageTitle) == "pastors_fund")
															<th>Pastor</th>																
														@endif
														<th>Church</th>
														<th>Sub Region</th>
														@if ($region == null)
															<th>Region</th>
														@endif
														<th>Amount</th>
														<th>Month</th>
														<th>Year</th>
													</tr>
												</thead>
												<tbody>
													@php($TOTALS = 0)
													@foreach ($mms['items'] as $k => $v)
														@php($TOTALS += floatval($v["amount"]))
														<tr>
															<td>
																<input type="checkbox" wire:key="{{ $v["id"] }}" name="breakdown" wire:click='toggleSelected("{{ $reg.$sub }}", "{{ $v['id'] }}")' 
																	value="{{ $v["id"] }}" {{ isset($selectedList[$reg.$sub][$v['id']]) && $selectedList[$reg.$sub][$v['id']] ? 'checked' : '' }} />
															</td>
															@if (strtolower($pageTitle) == "pastors_fund")
																<td>{{ isset($v["pastor"]) ? $v["pastor"]['contact']["name"] : 'n/a' }}</td>
															@endif
															<td>{{ isset($v["church"]) ? $v["church"]["name"] : 'n/a' }}</td>
															@if ($region == null)
																<td>{{ $v["region"] ? $v["region"]["name"] : 'n/a' }}</td>
															@endif
															<td>{{ $v["subregion"] ? $v["subregion"]["name"] : 'n/a' }}</td>
															<td>@money($v["amount"], setting('default.currency'), true)</td>
															<td>{{ $v["month"] }}</td>
															<td>{{ $v["year"] }}</td>
														</tr>
													@endforeach
												</tbody>
												<tfoot>
													<tr class="table-head-line">
														<th colspan="4" class="float-right">TOTAL</th>
														<th>@money($TOTALS, setting('default.currency'), true)</th>
														<th colspan="2"></th>
													</tr>
												</tfoot>
											</table>
										</div>
									</div>	
								@endforeach
							</div>
						</div>
					@endforeach
				</ul>
			</div>
		</div>
	@endif
	
	<!-- Modal -->
	<div class="modal fade" wire:ignore.self id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="addPaymentModalLabel"
	aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addPaymentModalLabel">Add Payment</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form>
						<div class="form-group">
							<label for="total">Total</label>
							<input type="text" class="form-control" id="total" wire:model='grandTotal' placeholder="Total" readonly />
						</div>
						<div class="form-group">
							<label for="chequeNumber">Cheque Number</label>
							@foreach ($chequeNumbers as $key=>$item)
							<div class="p-1">
								<small>{{ $key }} - {{ $item }}</small>
							</div>
							@endforeach
						</div>
						<div class="form-group">
							<label for="date">Date</label>
							<input type="date" class="form-control" wire:model='pay_date' id="date" />
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-success" data-dismiss="modal" wire:click="makePayment">Save</button>
				</div>
			</div>
		</div>
	</div>
</div>