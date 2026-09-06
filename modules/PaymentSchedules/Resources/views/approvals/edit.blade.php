@extends('layouts.admin')

@section('title', trans('payment-schedules::general.approval_configuration'))

@section('content')
	<div class="card">
		<form method="POST" action="{{ route('payment-schedules.approvals.update') }}">
			@csrf
			<div class="card-body">
				<div class="row">
					{{ Form::textGroup('name', trans('general.name'), 'font', ['required' => 'required'], optional($config)->name ?? 'M-Pesa payouts') }}
					<div class="form-group col-md-6">
						<label class="control-label">{{ trans('general.enabled') }}</label>
						<select name="enabled" class="form-control">
							<option value="1" {{ optional($config)->enabled ?? 1 ? 'selected' : '' }}>{{ trans('general.yes') }}</option>
							<option value="0" {{ optional($config)->enabled === 0 || optional($config)->enabled === false ? 'selected' : '' }}>{{ trans('general.no') }}</option>
						</select>
					</div>
				</div>
				<p class="text-muted">{{ trans('payment-schedules::general.final_step_hint') }}</p>
				<div class="d-flex justify-content-between align-items-center mb-2">
					<h4 class="mb-0">{{ trans_choice('payment-schedules::general.approval_step', 2) }}</h4>
					<button type="button" class="btn btn-sm btn-outline-primary" id="add-step">
						<i class="fas fa-plus"></i>
					</button>
				</div>
				<table class="table table-flush">
					<thead class="thead-light">
						<tr>
							<th>{{ trans('payment-schedules::general.level') }}</th>
							<th>{{ trans('general.name') }}</th>
							<th>{{ trans('payment-schedules::general.approver_type') }}</th>
							<th>{{ trans('payment-schedules::general.approver') }}</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="steps-body"></tbody>
				</table>
			</div>
			<div class="card-footer">
				<button type="submit" class="btn btn-success">{{ trans('general.save') }}</button>
			</div>
		</form>
	</div>
@endsection

@push('scripts_start')
<script>
	var users = @json($userOptions);
	var roles = @json($roleOptions);
	var existing = @json($existingSteps);

	function options(list, selected) {
		return list.map(function (item) {
			return `<option value="${item.id}" ${String(item.id) === String(selected) ? 'selected' : ''}>${item.name}</option>`;
		}).join('');
	}

	function stepRow(data, index) {
		data = data || {};
		var type = data.approver_type || 'user';
		var list = type === 'role' ? roles : users;
		return $(`
			<tr>
				<td class="level-cell">${index + 1}</td>
				<td><input type="text" name="steps[${index}][name]" class="form-control form-control-sm" required value="${data.name || ''}"></td>
				<td>
					<select name="steps[${index}][approver_type]" class="form-control form-control-sm approver-type">
						<option value="user" ${type === 'user' ? 'selected' : ''}>{{ trans('payment-schedules::general.user') }}</option>
						<option value="role" ${type === 'role' ? 'selected' : ''}>{{ trans('payment-schedules::general.role') }}</option>
					</select>
				</td>
				<td>
					<select name="steps[${index}][approver_id]" class="form-control form-control-sm approver-id">
						${options(list, data.approver_id)}
					</select>
				</td>
				<td><button type="button" class="btn btn-sm btn-outline-danger remove-step"><i class="fas fa-trash"></i></button></td>
			</tr>
		`);
	}

	function reindex() {
		$('#steps-body tr').each(function (index) {
			$(this).find('.level-cell').text(index + 1);
			$(this).find('[name]').each(function () {
				this.name = this.name.replace(/steps\[\d+]/, 'steps[' + index + ']');
			});
		});
	}

	$(function () {
		if (!existing.length) {
			$('#steps-body').append(stepRow({}, 0));
		} else {
			existing.forEach(function (step, index) {
				$('#steps-body').append(stepRow(step, index));
			});
		}

		$('#add-step').on('click', function () {
			$('#steps-body').append(stepRow({}, $('#steps-body tr').length));
			reindex();
		});

		$('#steps-body').on('click', '.remove-step', function () {
			if ($('#steps-body tr').length === 1) {
				return;
			}
			$(this).closest('tr').remove();
			reindex();
		});

		$('#steps-body').on('change', '.approver-type', function () {
			var type = $(this).val();
			var select = $(this).closest('tr').find('.approver-id');
			select.html(options(type === 'role' ? roles : users, null));
		});
	});
</script>
@endpush
