@extends('layouts.admin')

@section('title', trans('church::general.settings_name'))

@section('content')
	<div class="card">
		{!! Form::open([
			'route' => 'church.settings.update',
			'id' => 'church-settings',
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
				@foreach ($types as $typ)
					{{ Form::textGroup($typ, trans($names[$typ]), 'cog', ['required'=>'required' ], setting($keys[$typ])) }}
				@endforeach
			</div>
			<div class="row mt-3">
				<div class="col-md-12">
					<h4 class="border-bottom pb-2"><i class="fas fa-university"></i> Bank EFT Export Defaults</h4>
				</div>
				{{ Form::textGroup('eft_debit_account', trans('demarcations::general.eft_debit_account'), 'credit-card', [], setting('demarcations.eft_debit_account') ?: '0102092519200') }}
				{{ Form::textGroup('eft_purpose_code', trans('demarcations::general.eft_purpose_code'), 'hashtag', [], setting('demarcations.eft_purpose_code') ?: '051') }}
			</div>
			<div class="row mt-2 mx-1">
				<div class="w-100 border-bottom">
					<h4 class="float-left">
						<i class="fas fa-cogs"></i> Church Payment Division
					</h4>
					<div class="btn btn-transparent text-warning btn-sm float-right" id="add-payment-division-row"><i class="fas fa-plus"></i></div>
				</div>
				<div class="table-responsive custom">
					<table class="table table-flush table-hover">
						<thead class="thead-light">
							<tr class="table-head-line">
								<th colspan="2">NAME</th>
								<th colspan="2">CODE</th>
								<th colspan="1">CONTRIBUTION</th>
								<th colspan="1"></th>
							</tr>
						</thead>
						<tbody id="division-row" data-divisions="{{ json_encode($divisions) }}"></tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="card-footer">
			<div class="row save-buttons">
				{{ Form::saveButtons('settings.index') }}
			</div>
		</div>
	{!! Form::close() !!}
	</div>
@endsection

@push('scripts_start')
	<script>
		var theRow = function(data={}){
			var $no = Math.ceil(Math.random()*Math.random()*100000);
			var $tr = $(`
				<tr>
					<td colspan="2">
						<div class="form-group">
							<div class="input-group input-group-merge">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fas fa-solid fa-font"></i>
									</span>
								</div>
								<input type="text" class="form-control pd-name" required value="${data.name || ''}" name="payment_divisions[${$no}][name]" required placeholder="Enter Name...">
							</div>
						</div>
					</td>
					<td colspan="2">
						<div class="form-group">
							<div class="input-group input-group-merge">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fas fa-solid fa-font"></i>
									</span>
								</div>
								<input type="text" class="pl-2 form-control pd-code" value="${data.code || ''}" readonly="true" name="payment_divisions[${$no}][code]" required placeholder="Enter Code...">
							</div>
						</div>
					</td>
					<td colspan="1">
						<div class="form-group">
							<div class="input-group input-group-merge">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fas fa-solid fa-percentage"></i>
									</span>
								</div>
								<input type="number" min="0" max="100" required value="${data.percentage || ''}" class="form-control pd-perc" name="payment_divisions[${$no}][percentage]" required placeholder="Enter Percentage...">
							</div>
						</div>
					</td>
					<td colspan="1">
						<div class="form-group">
							<span class="btn btn-transparent text-danger delete-row"><i class="fas fa-trash"></i></span>
						</div>
					</td>
				</tr>
			`);

			var $trr = $tr.clone();

			$trr.on('keyup', '.pd-name', function(){
				var name = $(this).val();

				var nameParts = name.split(' ');

				nameParts = nameParts.map(function($v){
					return $.trim($v);
				});

				$trr.find('.pd-code').val(nameParts.join('_').toLowerCase());
			});

			$trr.on('click', '.delete-row', function(){
				$trr.remove();
			});

			return $trr;
		}

		$(function(){
			var $DIVISIONS = $('#division-row').data('divisions');
			$('#add-payment-division-row').on('click', function(){
				var newRow = theRow();

				if($('#division-row').find('tr.empty').length > 0){
					$('#division-row').empty();	
				}

				$('#division-row').append(newRow);
			});

			if($.isEmptyObject($DIVISIONS)){
				$('#division-row').append(`
					<tr class="empty">
						<td colspan="6" class="m-2 text-center">
							<i class="fas fa-info-circle"></i> No payment divisions configured.
						</td>
					</tr>
				`);
			}

			$.each($DIVISIONS, function(d, div){
				var newRow = theRow(div);
				$('#division-row').append(newRow);
			});
		});
	</script>
    {{-- <script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script> --}}
@endpush
				