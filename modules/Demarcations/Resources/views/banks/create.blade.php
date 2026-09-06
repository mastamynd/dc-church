@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('demarcations::general.bank', 1)]))

@section('content')
	<div class="card">
		{!! Form::open([
			'route' => 'demarcations.banks.store',
			'id' => 'bank',
			'@submit.prevent' => 'onSubmit',
			'@keydown' => 'form.errors.clear($event.target.name)',
			'role' => 'form',
			'class' => 'form-loading-button needs-validation',
			'novalidate' => true
		]) !!}

		<div class="card-body">
			<div class="row">
				{{ Form::textGroup('name', trans('general.name'), 'university', ['required' => 'required']) }}
				{{ Form::textGroup('code', trans('demarcations::general.code'), 'barcode', ['required' => 'required']) }}
				{{ Form::radioGroup('enabled', trans('general.enabled'), true) }}
			</div>
		</div>

		<div class="card-footer">
			<div class="row save-buttons">
				{{ Form::saveButtons('demarcations.banks.index') }}
			</div>
		</div>

		{!! Form::close() !!}
	</div>
@endsection

@push('scripts_start')
	<script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script>
@endpush
