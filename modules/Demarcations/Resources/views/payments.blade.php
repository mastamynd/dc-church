@extends('layouts.admin')

@section('title', 'Payments Made')

@section('content')
	@php($keys = array_keys(json_decode(json_encode($divisions), true)))
	@php(sort($keys))
	<form class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-4">
			<div class="form-group form-group-sm">
				<label class="form-label">Region</label>					
				@php($nReg = request()->has('region') ? request()->region : null)
				<select name="region" placeholder="Select Region..." class="form-control form-control selectize-field">
					<option value="">Select Region...</option>
					@foreach ($allregs as $rr)
						<option value="{{ $rr->id }}" {{ $nReg == $rr->id ? 'selected' : '' }}>{{ $rr->name }}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label class="form-label">&nbsp;&nbsp;</label>
				<button class="btn btn-success btn-block"><i class="fas fa-searcg"></i> FIND</button>
			</div>
		</div>
	</form>
	<livewire:make-payments.index :region="$nReg" />
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

		$('.card-header.reg').on('click', function(e){
			var target = $(this);
			var $div = target.data('div');
			var $id = target.data('id');

			var childrenCards = $(this).parents('.card').find('.collapse').find('.card');

			var $HEADER = $div != "pastors_fund" ? $(`
				<tr class="table-head-line">
					<th>No</th>
					<th>Church</th>
					<th>Pastor</th>
					<th>Contribution</th>
					<th>Month</th>
					<th>Year</th>
				</tr>
			`): $(`
				<tr class="table-head-line">
					<th>No</th>
					<th>Pastor</th>
					<th>Church</th>
					<th>Contribution</th>
					<th>Month</th>
					<th>Year</th>
				</tr>
			`);

			$('.the-empty-tt').find('thead').html($HEADER);

			$.ajax({
				url: `{{ route('demarcations.payments.distribution') }}/${$div}/${$id}`,
				beforeSend: function(){
					$.each(childrenCards, function(){
						$(this).find('table').find('tbody').html(`<tr><td colspan="100%"><i class="fas fa-spin fa-spinner"></i>Fetching Data...</td> </tr>`);
					});
				},
				success: function(js){
					console.log(js.data);
					$.each(js.data, function(c,v){
						var tID = "#table-"+c;
						var tTotal = 0;
						$(tID).find('tbody').empty();
						var index = 0;
						$.each(v, function(j,d){
							tTotal += parseFloat(d.amount);
							index++;
							var ROW = $div != "pastors_fund" ? $(`<tr data-amount="${parseFloat(d.amount)}">
								<td><input type="checkbox" name="church" value="${d.id}" checked /></td>
								<td>${ d.church.pastor ? d.church.pastor.contact : '-' }</td>
								<td>${ d.church.name }</td>
								<td>${ parseFloat(d.amount).toFixed(1) }</td>
								<td>${ d.month }</td>
								<td>${ d.year }</td>
							</tr>`) : $(`<tr data-amount="${parseFloat(d.amount)}">
								<td><input type="checkbox" name="church" value="${d.id}" checked /></td>
								<td>${ d.church.pastor ? d.church.pastor.contact : '-' }</td>
								<td>${ d.church.name }</td>
								<td>${ parseFloat(d.amount).toFixed(1) }</td>
								<td>${ d.month }</td>
								<td>${ d.year }</td>
							</tr>`);
							var nROW = ROW.clone();
							$(tID).find('tbody').append(nROW);

							nROW.find('[type="checkbox"]').on('change', function(){
								var tTOTALS = 0;
								$(tID).find('tbody').find('tr').find('[type="checkbox"]:checked').each(function(){
									var amnt = parseFloat($(this).parents('tr').data('amount'));
									amnt = isNaN(amnt) ? amnt : 0;
									tTOTALS +=amnt;
									$(tID).parents('.card').find('.sub-amount-total').text(parseFloat(tTOTALS).toFixed(1));
								});
							});
						});

						$(tID).parents('.card').find('.sub-amount-total').text(parseFloat(tTotal).toFixed(1));

						$(tID).find('tfoot').html(`
							<tr>
								<td colspan="3" style="text-align: right"><strong>TOTAL</strong></td>
								<td><strong>${ parseFloat(tTotal).toFixed(1) }</strong></td>
								<td></td>
								<td></td>
							</tr>
						`);

					});
				}
			})
		});
	});
</script>
	{{-- <script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script> --}}
@endpush