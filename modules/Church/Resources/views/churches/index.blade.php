@extends('layouts.admin')

@section('title', trans_choice('church::general.title', 2))

@section('new_button')
	@can('create-church-main')
		<a href="{{ route('church.create') }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
	@endcan
	<a href="{{ route('church.export', request()->input()) }}" class="btn btn-white btn-sm">{{ trans('general.export') }}</a>
@endsection

@section('content')
	@if ($churches->count() || request()->get('search', false))
		<div class="card">
			<div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
				{!! Form::open([
					'method' => 'GET',
					'route' => 'church.index',
					'role' => 'form',
					'class' => 'mb-0'
				]) !!}
					<div class="align-items-center" v-if="!bulk_action.show">
						<x-search-string model="Modules\Church\Models\ChurchView" />
					</div>

					{{ Form::bulkActionRowGroup('church::general.name', $bulk_actions, ['group' => 'church', 'type' => 'churches']) }}
				{!! Form::close() !!}
			</div>

			<div class="table-responsive custom">
				<table class="table table-flush table-hover">
					<thead class="thead-light">
						<tr class="table-head-line">
							<th>{{ Form::bulkActionAllGroup() }}</th>
							<th>@sortablelink('name', trans('church::general.name'))</th>
							<th>@sortablelink('code', trans('church::general.code'))</th>
							<th>@sortablelink('region', trans_choice('church::general.region', 1))</th>
							<th>@sortablelink('sub_region', trans_choice('church::general.subregion', 1))</th>
							<th>@sortablelink('pastor', trans_choice('church::general.pastor', 1))</th>
							<th>{{ trans('general.actions') }}</th>
						</tr>
					</thead>

					<tbody>
						@foreach($churches as $item)
							<tr class="align-items-center border-top-1">
								<td>
									{{ Form::bulkActionGroup($item->id, $item->name) }}
								</td>
								<td>
									<a class="long-texts" href="{{ route('church.show', $item->id) }}">{{ $item->name }}</a>
								</td>
								<td>
									<a class="long-texts" href="{{ route('church.show', $item->id) }}">{{ $item->code }}</a>
								</td>
								<td>
									@if(trim($item->region) != "")
										<a class="long-texts" href="{{ route('demarcations.show', ['Region', $item->region_id]) }}">{{ $item->region }}</a>
									@else
										<span class="badge badge-warning badge-sm">Not Set</span>
									@endif
								</td>
								<td>
									@if(trim($item->sub_region) != "")
										<a class="long-texts" href="{{ route('demarcations.show', ['Sub Region', $item->sub_region_id]) }}">{{ $item->sub_region }}</a>
									@else
										<span class="badge badge-warning badge-sm">Not Set</span>
									@endif
								</td>
								<td class="long-texts">
									@if(trim($item->pastor) != "")
										<a href="{{route('church.pastors.show',$item->pastor_id)}}">
											<small class="badge badge-pill badge-primary">{{ $item->rank }}</small>
											{{ $item->pastor }}
										</a>
									@else
										<span class="badge badge-warning badge-sm">Not Set</span>
									@endif
								</td>
								<td class="text-center">
									<div class="dropdown">
										<a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<i class="fa fa-ellipsis-h text-muted"></i>
										</a>
										<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
											<a class="dropdown-item" href="{{ route('church.show', $item->id) }}">
												{{ trans('general.show') }}
											</a>

											@can('update-church-main')
												<a class="dropdown-item" href="{{ route('church.edit', $item->id) }}">
													{{ trans('general.edit') }}
												</a>
											@endcan

											@can('delete-church-main')
												<span class="dropdown-item" data-target="#delete-church-modal" data-toggle="modal" data-href="{{ route('church.destroy', $item->id) }}">{{ trans('general.delete') }}</span>
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
					@include('partials.admin.pagination', ['items' => $churches])
				</div>
			</div>
		</div>
	@else
		<x-empty-page group="sales" page="churches" routeCreate="{{ route('church.create') }}" textEmptyPage="{{ trans('church::general.empty_page') }}" />
	@endif
@endsection

@push('scripts_start')
<script type="text/javascript">
	$(function(){
		$("#delete-church-modal").on("shown.bs.modal", function (e){ 
			var urll = $(e.relatedTarget).data('href');

			$(this).find('form').attr('action', urll);
			$(this).find('form').prop('action', urll);
		});
	});
</script>
	<div class="modal fade show" id="delete-church-modal"  role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method="post">
					@csrf
					<div class="modal-header border-bottom" style="background-color: #f8f9fe;">
						<i class="fas fa-trash"></i> Delete Church
					</div>
					<div class="modal-body">
						<div class="form-group form-group-lg">
							<div class="alert alert-danger">
								<i class="fas fa-exclamation-triangle fa-2x pull-left float-left"></i> Are you sure that you want to delete this church?
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
	<script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script>
@endpush
