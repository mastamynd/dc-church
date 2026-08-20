@extends('layouts.admin')

@section('title', 'Payment Distribution Made')

@section('content')
@php($nReg = request()->has('region') ? request()->region : null)
@php($nSub = request()->has('sub_region') ? request()->sub_region : null)
@php($region = $nReg)
@php($subregion = $nSub)
<form class="row">
	<div class="col-md-1">
	</div>
	<div class="col-md-4">
		<div class="form-group form-group-sm">
			<label class="form-label">Region</label>
			<select name="region" placeholder="Select Region..." class="form-control form-control-sm selectize-field">
				<option value="">Select Region...</option>
				@foreach ($regions as $rr)
				<option value="{{ $rr->id }}" {{ request()->region == $rr->id ? 'selected' : '' }}>{{ $rr->name }}</option>
				@endforeach
			</select>
		</div>
	</div>
	{{-- <div class="col-md-4">
		<div class="form-group form-group-sm">
			<label class="form-label">Sub Region</label>
			<select name="sub_region" placeholder="Select Sub Region..." class="form-control form-control-sm selectize-field">
				@empty($subs)
				<option disabled>Select a Region...</option>
				@endempty
				@foreach ($subs as $sub)
				<option value="{{ $sub->id }}" {{ $sub->id == request()->sub_region ? 'selected' : '' }}>{{ $sub->name }}
				</option>
				@endforeach
			</select>
		</div>
	</div> --}}
	<div class="col-md-2">
		<div class="form-group">
			<label class="form-label">&nbsp;&nbsp;</label>
			<button class="btn btn-sm btn-success btn-block"><i class="fas fa-searcg"></i> FIND</button>
		</div>
	</div>
</form>
<br>

<div class="card">
	<div class="card-header">
		<div class="d-flex justify-content-between">
			<div>
				<h2 class="card-title">
					<a class="btn btn-transparent btn-sm" href="{{ route('demarcations.make-payments-new') }}">
						<i class="fas fa-arrow-left"></i>
					</a>
					Make Payments <small><i class="fas fa-caret-right"></i> {{ $pageTitle }}</small>
				</h2>
			</div>
			@if ($hasPayAll)
			<input type="text" data-name="payment[{{ $type }}]" data-total="{{ $grandTotal }}" data-strName="{{ $type }}" data-value="{{ $type }}"
				class="form-control form-control-sm cheque-number" placeholder="Cheque Number..." style="width: 250px" />
			@endif
			<div class="pt-1">
				<span class="badge badge-dark badge-pill">@money($grandTotal, setting('default.currency'),
					true)</span>&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addPaymentModal">
					<i class="fas fa-hand-holding-usd"></i> Pay
				</span>
			</div>
		</div>
	</div>
</div>
@if (request()->has('region') && !request()->has('sub_region'))
<div class="card">
	<div class="card-header">
		<div class="title p-2 d-flex justify-content-between" style="cursor: pointer">
			<div style="width: 300px">
				<h4>{{ $RegionData->name }}</h4>
			</div>
			@if ($hasPayReg)
			<input type="text" data-total="{{ $grandTotal }}" data-strName="{{ $RegionData->name }}" data-name="payment[{{ $type }}][{{ request()->region }}]"
				class="form-control form-control-sm cheque-number" placeholder="Cheque Number..." style="width: 250px" />
			@endif
			<div class="pt-1">
				<span class="badge badge-dark badge-pill">@money($grandTotal,
					setting('default.currency'),true)</span>&nbsp;&nbsp;&nbsp;&nbsp;
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="row">
			@foreach ($paymentDivision as $item)
			<div class="title p-2 border-bottom d-flex justify-content-between" style="cursor: pointer; width: 100%">
				<div style="width: 300px">
					<h5>{{ $item->name }}</h5>
				</div>
				@if ($hasPaySub)
				<input type="text" data-strName="{{ $item->name }}" data-total="{{ $item->total }}"
					data-name="payment[{{ $type }}][{{ request()->region }}][{{ $item->id }}]"
					class="form-control form-control-sm cheque-number" placeholder="Cheque Number..." style="width: 250px" />
				@endif
				<div class="pt-1">
					<a class="badge badge-default badge-pill"
						href="{{ route('demarcations.payment-distribution-list', [$type]) }}?region={{ request()->region }}&sub_region={{ $item->id }}">
						@money($type == 'pastors_fund' ? $item->total + $item->topup :  $item->total, setting('default.currency'), true) &nbsp;&nbsp;&nbsp;&nbsp;
						<i class="fas fa-angle-right' }} text-muted"></i>
					</a>
				</div>
			</div>
			@endforeach
		</div>
	</div>
</div>
@endif
@if (request()->has('region') && request()->has('sub_region') && trim(request()->sub_region)!="")
<div class="card">
	<div class="card-header">
		<div class="title p-2 d-flex justify-content-between" style="cursor: pointer">
			<div style="width: 300px">
				<h4>{{ $RegionData->name }}</h4>
			</div>
			@if ($hasPaySub)
			<input type="text" data-strName="{{ $RegionData->name }}" data-total="{{ $grandTotal }}"
				data-name="payment[{{ $type }}][{{ request()->region }}][{{ request()->sub_region }}]"
				class="form-control form-control-sm" placeholder="Cheque Number..." style="width: 250px" />
			@endif
			<div class="pt-1">
				<span class="badge badge-dark badge-pill">@money($grandTotal,
					setting('default.currency'),true)</span>&nbsp;&nbsp;&nbsp;&nbsp;
			</div>
		</div>
	</div>
	<div class="card-body">
		<table class="table table-condensed">
			<thead>
				<tr>
					<th>Name</th>
					<th>Pastor</th>
					<th>Amount</th>
					@if ($type == 'pastors_fund')
						<th>ID No</th>
					@endif
					<th>Month</th>
					<th>Year</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($paymentDivision as $item)
				<tr>
					<td>{{ $item->church->name }}</td>
					@if ($item->pastor)
					<td>{{ $item->pastor->contact->name }}</td>
					@else
					<td>{{ $item->senior }}</td>
					@endif
					<td>@money($type == 'pastors_fund' ? $item->total + $item->topup :  $item->total, setting('default.currency'), true)</td>
					@if ($type == 'pastors_fund')
					<td>{{ $item->pastor->id_or_passport }}</td>
					@endif
					<td>{{ $item->month }}</td>
					<td>{{ $item->year }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endif
@if(!request()->has('region') && !request()->has('sub_region'))
<div class="card">
	<div class="card-header">
		<div class="title p-2 d-flex justify-content-between" style="cursor: pointer; width: 100%">
			<div style="width: 300px">
				<h4>Regions</h4>
			</div>
			@if ($hasPayReg)
			<input type="text" data-total="{{ $grandTotal }}" data-name="payment[{{ $type }}]" data-strName="{{ $type }}"
				class="form-control form-control-sm cheque-number" placeholder="Cheque Number..." style="width: 250px" />
			@endif
			<div class="pt-1">
				<span class="badge badge-default badge-pill">@money($grandTotal, setting('default.currency'),true)</span>
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="row">
			@foreach ($paymentDivision as $item)
			<div class="title p-2 border-bottom d-flex justify-content-between" style="cursor: pointer; width: 100%">
				<div style="width: 300px">
					<h5>{{ $item->name }}</h5>
				</div>
				@if ($hasPayReg)
				<input type="text" data-total="{{ $item->total }}" data-name="payment[{{ $type }}][{{ $item->id }}]" data-strName="{{ $item->name }}"
					class="form-control form-control-sm cheque-number" placeholder="Cheque Number..." style="width: 250px" />
				@endif
				<div class="pt-1">
					<a class="badge badge-default badge-pill"
						href="{{ route('demarcations.payment-distribution-list', [$type]) }}?region={{ $item->id }}">
						@money($type == 'pastors_fund' ? $item->total + $item->topup :  $item->total, setting('default.currency'), true) &nbsp;&nbsp;&nbsp;&nbsp;
						<i class="fas fa-angle-right text-muted"></i>
					</a>
				</div>
			</div>
			@endforeach
		</div>
	</div>
</div>
@endif
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
	.selectize-dropdown,
	.selectize-input {
		line-height: 18px;
		padding: 13px;
	}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js"
	integrity="sha512-pF+DNRwavWMukUv/LyzDyDMn8U2uvqYQdJN0Zvilr6DDo/56xPDZdDoyPDYZRSL4aOKO/FGKXTpzDyQJ8je8Qw=="
	crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
							`<div class="p-2"><i class="far ${$icon}"></i> ${item[$labelField]}</div>`
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
		$('select[name="region"]').on('change', function(){
			let reg = $(this).val();
			$.ajax({
				url: "{{ route('demarcations.sub-region-list') }}/"+reg,
				dataType:"json",
				beforeSend: function(){
					$('select[name="sub_region"]').html('<option>Fetching Regions...</option>');
				},
				success: function(js){
					$('select[name="sub_region"]').html('<option value="">Select Sub Region...</option>');
					$.each(js, function(j,s){
						$('select[name="sub_region"]').append(`<option value='${s.id}'>${s.name}</option>`);
					});
				}
			})
		});

		$('#addPaymentModal').on('shown.bs.modal', function (e) {
			$('#all-cheques').empty();
			let TOTAL = 0;
			$('input.cheque-number').each(function(){
				let name = $(this).data('name');
				let str = $(this).data('strname');
				let val = $(this).val();
				let amnt = $(this).data('total');


				if(val.trim() != ""){
					TOTAL+=parseFloat(amnt);
					$('#all-cheques').append(`<div class="col-12 p-1 ml-2 text-sm">
						${str} - ${val}</div>
						<input type="hidden" name="${name}" value="${val}" />
					`);
				}
			});

			$('#total').val(TOTAL);
		});
	});
	
</script>
{{-- <script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script> --}}
<div class="modal fade" wire:ignore.self id="addPaymentModal" tabindex="-1" role="dialog"
	aria-labelledby="addPaymentModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<form method="POST" action="{{ route('demarcations.pay-cheque-numbers') }}" class="modal-content">
			@csrf
			<div class="modal-header">
				<h5 class="modal-title" id="addPaymentModalLabel">Add Payment</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="total">Total</label>
					<input type="text" class="form-control" id="total" placeholder="Total" readonly />
				</div>
				<div class="form-group">
					<label for="chequeNumber">Cheque Number</label>
					<div class="row" id="all-cheques"></div>
				</div>
				{{-- @if(request()->region)
				<input type="hidden" name="region" value="{{ request()->region }}" />
				@endif

				@if(request()->sub_region)
				<input type="hidden" name="sub_region" value="{{ request()->sub_region }}" />
				@endif

				@if(request()->type)
				<input type="hidden" name="type" value="{{ request()->type }}" />
				@endif --}}
				<div class="form-group">
					<label for="date">Date</label>
					<input type="date" class="form-control" name='pay_date' id="date" required/>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-success" >Save</button>
			</div>
		</form>
	</div>
</div>
@endpush