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
						<x-search-string model="Modules\Church\Models\Church" />
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
							<th>@sortablelink('subregion', trans_choice('church::general.subregion', 1))</th>
							<th>@sortablelink('pastor', trans_choice('church::general.pastor', 1))</th>
							<th>@sortablelink('enabled', trans('general.enabled'))</th>
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
									@if(isset($item->region) && isset($item->region->demarcation))
										<a class="long-texts" href="{{ route('church.show', $item->region->demarcation->id) }}">{{ $item->region->demarcation->name }}</a>
									@else
										<span class="badge badge-warning badge-sm">Not Set</span>
									@endif
								</td>
								<td>
									@if(isset($item->subregion) && isset($item->subregion->demarcation))
										<a class="long-texts" href="{{ route('church.show', $item->subregion->demarcation->id) }}">{{ $item->subregion->demarcation->name }}</a>
									@else
										<span class="badge badge-warning badge-sm">Not Set</span>
									@endif
								</td>
								<td class="long-texts">
									<el-tooltip content="{{ !empty($item->phone) ? $item->phone : trans('general.na') }}"
										effect="dark"
										placement="top">
										<span>{{ !empty($item->email) ? $item->email : trans('general.na') }}</span>
									</el-tooltip>
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
											<a class="dropdown-item" href="{{ route('church.show', $item->id) }}">
												{{ trans('general.show') }}
											</a>

											@can('update-church-main')
												<a class="dropdown-item" href="{{ route('church.edit', $item->id) }}">
													{{ trans('general.edit') }}
												</a>
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
	<script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script>
@endpush
