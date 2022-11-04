@extends('layouts.admin')

@php($isSubRegion = in_array($type, ["Sub Region", "Sub-Region", 'subregion']))

@section('title', trans_choice((!$isSubRegion ? 'demarcations::general.region' : 'demarcations::general.subregion'), 2))

@section('new_button')
	@can('create-demarcations-main')
		<a href="{{ route('demarcations.create', $type) }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
	@endcan
	<a href="{{ route('demarcations.export', request()->input()) }}" class="btn btn-white btn-sm">{{ trans('general.export') }}</a>
@endsection

@section('content')
	@if ($demarcations->count() || request()->get('search', false))
		<div class="card">
			<div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
				{!! Form::open([
					'method' => 'GET',
					'route' => $type == 'region' ? 'demarcations.region.index' : 'demarcations.subregion.index',
					'role' => 'form',
					'class' => 'mb-0'
				]) !!}
					<div class="align-items-center" v-if="!bulk_action.show">
						<x-search-string model="Modules\Church\Models\Demarcation" />
					</div>
					{{-- {{ Form::bulkActionRowGroup('demarcations::general.name', $bulk_actions, ['group' => $type, 'type' => 'demarcations']) }} --}}
				{!! Form::close() !!}
			</div>

			<div class="table-responsive custom">
				<table class="table table-flush table-hover">
					<thead class="thead-light">
						<tr class="table-head-line">
							<th>{{ Form::bulkActionAllGroup() }}</th>
							<th>@sortablelink('name', trans('general.name'))</th>
							<th>@sortablelink('code', trans('demarcations::general.code'))</th>
							{{-- @if ($type == "subregion")
								<th>@sortablelink('code', trans_choice('demarcations::general.region', 1))</th>
							@endif --}}
							<th class="text-right">@sortablelink('subregion', trans_choice((!$isSubRegion ? 'demarcations::general.subregion' : 'demarcations::general.church'), 2))</th>
							<th>@sortablelink('enabled', trans('general.enabled'))</th>
							<th>{{ trans('general.actions') }}</th>
						</tr>
					</thead>

					<tbody>
						@foreach($demarcations as $item)
							<tr class="align-items-center border-top-1">
								<td>
									{{ Form::bulkActionGroup($item->id, $item->name) }}
								</td>
								<td>
									<a class="long-texts" href="{{ route('demarcations.show', [$type, $item->id]) }}">{{ $item->name }}</a>
								</td>
								<td>
									<a class="long-texts" href="{{ route('demarcations.show', [$type, $item->id]) }}">{{ $item->code }}</a>
								</td>
								{{-- @if ($type == "subregion")
									<td>
										<a href="{{ route('demarcations.show', [$item->parent->parent_id, $item->parent->parent_id]) }}">{{ $item->parent_id }}</a>
									</td>
								@endif --}}
								<td class="text-right">
									{{ $item->children->count() }}
									{{-- {{ number_format(!$isSubRegion ? $item->children->count() : $item->churches(true)) }} --}}
								</td>
								<td class="">
									@if (user()->can('update-church-main'))
										{{ Form::enabledGroup($item->id, $item->name, $item->enabled) }}
									@else
										@if ($item->enabled)
											<badge rounded type="success" class="mw-60 d-inline-block">{{ trans('general.yes') }}</badge>
										@else
											<badge rounded type="danger" class="mw-60 d-inline-block">{{ trans('general.no') }}</badge>
										@endif
									@endif
								</td>
								<td class="text-center">
									<div class="dropdown">
										<a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<i class="fa fa-ellipsis-h text-muted"></i>
										</a>
										<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
											<a class="dropdown-item" href="{{ route('demarcations.show', [$type, $item->id]) }}">
												{{ trans('general.show') }}
											</a>

											@can('update-demarcations-main')
												<a class="dropdown-item" href="{{ route('demarcations.edit', [$type, $item->id]) }}">
													{{ trans('general.edit') }}
												</a>
											@endcan
											
											@can('delete-demarcations-main')
												<span class="dropdown-item" data-target="#delete-demarcation-modal" data-toggle="modal" data-href="{{ route('demarcations.delete', $item->id) }}">{{ trans('general.delete') }}</span>
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
					@include('partials.admin.pagination', ['items' => $demarcations])
				</div>
			</div>
		</div>
	@else
		@php($demID = ['region'=>1, 'subregion'=>2])
		<x-empty-page group="demarcations" page="demarcations" routeCreate="{{ route('demarcations.create', $type) }}" textEmptyPage="{{ trans_choice('demarcations::general.empty_page', 1) }}" />
	@endif
@endsection

@push('scripts_start')
	<script type="text/javascript">
	$(function(){
		$("#delete-demarcation-modal").on("shown.bs.modal", function (e){ 
			var urll = $(e.relatedTarget).data('href');

			$(this).find('form').attr('action', urll);
			$(this).find('form').prop('action', urll);
		});
	});
	</script>
	<div class="modal fade show" id="delete-demarcation-modal"  role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method="post">
					@csrf
					<div class="modal-header border-bottom" style="background-color: #f8f9fe;">
						<i class="fas fa-trash"></i> Delete
					</div>
					<div class="modal-body">
						<div class="form-group form-group-lg">
							<div class="alert alert-danger">
								<i class="fas fa-exclamation-triangle fa-2x pull-left float-left"></i> Are you sure that you want to delete this?
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
