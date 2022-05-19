@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice(($type == "region" ? 'demarcations::general.region' : 'demarcations::general.subregion'), 1)]))

@section('content')
	<div class="card">
		{!! Form::open([
			'route' => ['demarcations.store', [$type,'create']],
			'id' => 'demarcations',
			'@submit.prevent' => 'onSubmit',
			'@keydown' => 'form.errors.clear($event.target.name)',
			'files' => true,
			'role' => 'form',
			'autocomplete' => "off",
			'class' => 'form-loading-button needs-validation',
			'novalidate' => 'true'
		]) !!}

		<div class="card-body">
			<div class="row">
				{{ Form::textGroup('name', trans('general.name'), 'demarcations', ['required'=>'required' ]) }}

				{{ Form::textGroup('code', trans('demarcations::general.code'), 'file', ['autocomplete' => 'off', 'readonly'=>true], $possible_next_code) }}

				@if ($type == "subregion")
				<div class="form-group form-group-lg col-md-6">
					<label class="control-label">{{ trans_choice('demarcations::general.region', 1) }}</label>
					<div class="input-group input-group-merge ">
						<div class="input-group-prepend">
							<span class="input-group-text">
								<i class="fa fa-user"></i>
							</span>
						</div>
						<select name="region_id" placeholder="Select {{ trans_choice('demarcations::general.region', 1) }}..."
							class="form-control selectize-field" data-searchfield="name" data-labelfield="name" data-valuefield="id">
							<option>Select {{ trans_choice('demarcations::general.region', 1) }}...</option>
							@foreach ($regions as $r)
								<option value="{{ $r->id }}">{{ $r->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
				@endif
			</div>
		</div>

		<div class="card-footer">
			<div class="row save-buttons">
				{{ Form::saveButtons($type == 'region' ? 'demarcations.region.index' : 'demarcations.subregion.index') }}
			</div>
		</div>

		{{ Form::hidden('type', $type == 'region' ? 'Region' : 'Sub Region' ) }}

		{!! Form::close() !!}
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