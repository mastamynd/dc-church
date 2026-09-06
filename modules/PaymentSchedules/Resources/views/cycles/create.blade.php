@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('payment-schedules::general.billing_cycle', 1)]))

@section('content')
	<div class="card">
		{!! Form::open([
			'route' => 'payment-schedules.cycles.store',
			'id' => 'billing-cycle',
			'role' => 'form',
			'class' => 'form-loading-button'
		]) !!}

		<div class="card-body">
			<div class="row">
				{{ Form::textGroup('name', trans('general.name'), 'font', ['required' => 'required']) }}
				<div class="form-group col-md-6">
					<label class="control-label">{{ trans('payment-schedules::general.scheduled_date') }}</label>
					<input type="date" name="scheduled_date" class="form-control" required value="{{ request('scheduled_date', date('Y-m-d')) }}">
				</div>
				{{ Form::textareaGroup('notes', trans('payment-schedules::general.notes'), 'sticky-note-o') }}
			</div>
		</div>

		<div class="card-footer">
			<div class="row save-buttons">
				<div class="col-md-12">
					<a href="{{ route('payment-schedules.cycles.index') }}" class="btn btn-icon btn-outline-secondary">
						<span class="btn-inner--text">{{ trans('general.cancel') }}</span>
					</a>
					<button type="submit" class="btn btn-icon btn-success">
						<span class="btn-inner--text">{{ trans('general.save') }}</span>
					</button>
				</div>
			</div>
		</div>

		{!! Form::close() !!}
	</div>
@endsection
