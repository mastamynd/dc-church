@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => $cycle->name]))

@section('content')
	<div class="card">
		{!! Form::model($cycle, [
			'route' => ['payment-schedules.cycles.update', $cycle->id],
			'method' => 'PATCH',
			'id' => 'billing-cycle',
			'role' => 'form',
			'class' => 'form-loading-button'
		]) !!}

		<div class="card-body">
			<div class="row">
				{{ Form::textGroup('name', trans('general.name'), 'font', ['required' => 'required'], $cycle->name) }}
				<div class="form-group col-md-6">
					<label class="control-label">{{ trans('payment-schedules::general.scheduled_date') }}</label>
					<input type="date" name="scheduled_date" class="form-control" required value="{{ optional($cycle->scheduled_date)->format('Y-m-d') }}">
				</div>
				{{ Form::textareaGroup('notes', trans('payment-schedules::general.notes'), 'sticky-note-o', $cycle->notes) }}
			</div>
		</div>

		<div class="card-footer">
			<div class="row save-buttons">
				<div class="col-md-12">
					<a href="{{ route('payment-schedules.cycles.show', $cycle->id) }}" class="btn btn-icon btn-outline-secondary">
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
