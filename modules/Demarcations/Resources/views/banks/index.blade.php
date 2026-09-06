@extends('layouts.admin')

@section('title', trans_choice('demarcations::general.bank', 2))

@section('new_button')
	@can('create-demarcations-banks')
		<a href="{{ route('demarcations.banks.create') }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
		<a href="{{ route('import.create', ['demarcations', 'banks']) }}" class="btn btn-white btn-sm">{{ trans('import.import') }}</a>
	@endcan
	<a href="{{ route('demarcations.banks.export', request()->input()) }}" class="btn btn-white btn-sm">{{ trans('general.export') }}</a>
@endsection

@section('content')
	@if ($banks->count() || request()->get('search', false))
		<div class="card">
			<div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
				{!! Form::open([
					'method' => 'GET',
					'route' => 'demarcations.banks.index',
					'role' => 'form',
					'class' => 'mb-0'
				]) !!}
					<div class="align-items-center" v-if="!bulk_action.show">
						<x-search-string model="Modules\Demarcations\Models\Bank" />
					</div>
				{!! Form::close() !!}
			</div>

			<div class="table-responsive custom">
				<table class="table table-flush table-hover">
					<thead class="thead-light">
						<tr class="table-head-line">
							<th>@sortablelink('name', trans('general.name'))</th>
							<th>@sortablelink('code', trans('demarcations::general.code'))</th>
							<th>@sortablelink('enabled', trans('general.enabled'))</th>
							<th>{{ trans('general.actions') }}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($banks as $item)
							<tr class="align-items-center border-top-1">
								<td>
									<a class="long-texts" href="{{ route('demarcations.banks.edit', $item->id) }}">{{ $item->name }}</a>
								</td>
								<td>{{ $item->code }}</td>
								<td>
									@if (user()->can('update-demarcations-banks'))
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
											@can('update-demarcations-banks')
												<a class="dropdown-item" href="{{ route('demarcations.banks.edit', $item->id) }}">
													{{ trans('general.edit') }}
												</a>
											@endcan
											@can('delete-demarcations-banks')
												{!! Form::deleteLink($item, 'demarcations.banks.destroy', 'demarcations::general.bank') !!}
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
					@include('partials.admin.pagination', ['items' => $banks])
				</div>
			</div>
		</div>
	@else
		<x-empty-page
			group="demarcations"
			page="demarcations"
			routeCreate="demarcations.banks.create"
			permissionCreate="create-demarcations-banks"
			textEmptyPage="demarcations::general.empty_banks"
			textPage="demarcations::general.bank"
		/>
	@endif
@endsection

@push('scripts_start')
	<script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script>
@endpush
