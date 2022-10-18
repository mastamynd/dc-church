@extends('layouts.admin')

@section('title', trans_choice('church::general.pastor', 2))

@section('new_button')
@can('create-church-main')
<a href="{{route('church.pastors.create')}}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
<!-- <a href="" class="btn btn-white btn-sm">{{ trans('import.import') }}</a> -->
@endcan
<a href="{{ route('church.pastors.export', request()->input()) }}" class="btn btn-white btn-sm">{{ trans('general.export') }}</a>
@endsection

@section('content')
@if ($pastors->count() || request()->get('search', false))
<div class="card">
	<div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
		{!! Form::open([
		'method' => 'GET',
		'route' => 'church.pastors.index',
		'role' => 'form',
		'class' => 'mb-0'
		]) !!}
		<div class="align-items-center" v-if="!bulk_action.show">
			<x-search-string model="Modules\Church\Models\Pastor" />
		</div>

		<!-- {{ Form::bulkActionRowGroup('general.pastor', $bulk_actions, ['group' => 'pastor', 'type' => 'pastor']) }} -->
		{!! Form::close() !!}
	</div>

	<div class="table-responsive custom">
		<table class="table table-flush table-hover">
			<thead class="thead-light">
				<tr class="table-head-line">
					<th class="">{{ Form::bulkActionAllGroup() }}</th>
					<th class="">{{trans_choice('church::general.pastor', 1)}}</th>
					<th class="">{{trans('church::general.name')}}</th>
					<th class="">{{trans('church::general.passport')}}</th>
					<th class="">{{trans('general.enabled')}}</th>
					<th class="">{{trans('general.phone')}}</th>
					<th class="">{{trans('church::general.gender')}}</th>
					<th class="">#</th>
				</tr>
			</thead>
			<tbody>
				@foreach($pastors as $item)
				<tr class=" align-items-center border-top-1">
					<td class="">
						{{ Form::bulkActionGroup($item->id, $item->name) }}
					</td>
					<td class=""><a href="{{route('church.pastors.show',$item->id)}}">{{ $item->name }}</a></td>
					<td class="">{{$item->church}}</td>
					<td class="">{{$item->id_or_passport}}</td>
					<td class="">{{ Form::enabledGroup($item->id, $item->name, $item->enabled) }}</td>
					<td class="">{{$item->phone}}</td>
					<td class="">{{$item->gender}}</td>

					<td class="">
						<div class="dropdown">
							<a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fa fa-ellipsis-h text-muted"></i>
							</a>

							<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
								@can('update-church-main')
									<a class="dropdown-item" href="{{ route('church.pastors.edit', $item->id) }}">{{ trans('general.edit') }}</a>
								@endcan
								@can('delete-church-main')
									<span class="dropdown-item" data-target="#delete-pastor-modal" data-toggle="modal" data-href="{{ route('church.pastor.delete', $item->id) }}">{{ trans('general.delete') }}</span>
								@endcan
							</div>
						</div>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	<div class="card-footer table-action">
		<div class="row">
			@include('partials.admin.pagination', ['items' => $pastors])
		</div>
	</div>
</div>
@else
<x-empty-page group="projects" page="projects" route-create="{{ 'project-manager.edit' }}" image-empty-page="public/img/empty_pages/customers.png" text-empty-page="church::general.no_pastors_message" text-page="church::general.title" />
@endif
@endsection

@push('scripts_start')
<script type="text/javascript">
	$(function(){
		$("#delete-pastor-modal").on("shown.bs.modal", function (e){ 
			var urll = $(e.relatedTarget).data('href');

			$(this).find('form').attr('action', urll);
			$(this).find('form').prop('action', urll);
		});
	});
</script>
<script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script>
<div class="modal fade show" id="delete-pastor-modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post">
				@csrf
				<div class="modal-header border-bottom" style="background-color: #f8f9fe;">
					<i class="fas fa-trash"></i> Delete the Pastor
				</div>
				<div class="modal-body">
					<div class="form-group form-group-lg">
						<div class="alert alert-danger">
							<i class="fas fa-exclamation-triangle fa-2x pull-left float-left"></i> Are you sure that you want to delete this pastor?
						</div>
					</div>
				</div>
				<div class="modal-footer border-top" style="background-color: #f8f9fe;">
					<span class="btn btn-white" data-dismiss="modal">Close</span>
					<button type="submit" class="btn btn-danger">DELETE</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endpush