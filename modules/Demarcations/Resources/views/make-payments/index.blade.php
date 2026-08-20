@extends('layouts.admin')

@section('title', 'Payments Made')

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
						<option value="{{ $sub->id }}" {{ $sub->id == request()->sub_region ? 'selected' : '' }}>{{ $sub->name }}</option>
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
			<h2 class="card-title" style="width:100%">
				Make Payments 
				<a href="{{ route('payment-reception.export-payment-form') }}{{ $region !== null ? '?region='.$region : '' }}" 
				class="btn btn-success" style="float:right">Export</a>
			</h2>
		</div>
		<div class="card-body">
			<ul class="list-group list-group-flush">
				@foreach ($PaymentTypes as $k => $v)
					<li class="list-group-item d-flex justify-content-between align-items-center"
						style="cursor: pointer" wire:click="showType('{{ $v['code'] }}')">
						<h5>{{ $v['name'] }}</h5>
						<a class="righer" href="{{ route('demarcations.payment-distribution-list', [$v['code']]) }}">
							<span class="badge badge-dark badge-pill">@money($v['amount'] ?? 0, setting('default.currency'), true)</span>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<i class="fas fa-angle-right text-muted"></i>
						</a>
					</li>
				@endforeach
			</ul>
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
					$('select[name="sub_region"]').html('<option>Select Sub Region...</option>');
					$.each(js, function(j,s){
						$('select[name="sub_region"]').append(`<option value='${s.id}'>${s.name}</option>`);
					});
				}
			})
		});
	});
</script>
	{{-- <script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script> --}}
@endpush