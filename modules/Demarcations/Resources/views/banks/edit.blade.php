@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('demarcations::general.bank', 1)]))

@section('content')
	<div class="card">
		{!! Form::model($bank, [
			'method' => 'PATCH',
			'route' => ['demarcations.banks.update', $bank->id],
			'id' => 'bank',
			'@submit.prevent' => 'onSubmit',
			'@keydown' => 'form.errors.clear($event.target.name)',
			'role' => 'form',
			'class' => 'form-loading-button needs-validation',
			'novalidate' => true
		]) !!}

		<div class="card-body">
			<div class="row">
				{{ Form::textGroup('name', trans('general.name'), 'university', ['required' => 'required'], $bank->name) }}
				{{ Form::textGroup('code', trans('demarcations::general.code'), 'barcode', ['required' => 'required'], $bank->code) }}
				{{ Form::radioGroup('enabled', trans('general.enabled'), $bank->enabled) }}
			</div>
		</div>

		@can('update-demarcations-banks')
			<div class="card-footer">
				<div class="row save-buttons">
					{{ Form::saveButtons('demarcations.banks.index') }}
				</div>
			</div>
		@endcan

		{!! Form::close() !!}
	</div>
@endsection

@push('scripts_start')
	<script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script>
@endpush
