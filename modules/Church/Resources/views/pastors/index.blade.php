@extends('layouts.admin')

@section('title', trans_choice('church::general.pastor', 2))

@section('new_button')
@can('create-church-main')
<a href="{{route('church.pastors.create')}}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
<!-- <a href="" class="btn btn-white btn-sm">{{ trans('import.import') }}</a> -->
@endcan
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
			<x-search-string model="App\Models\Common\Contact" />
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
					<th class="">{{trans('church::general.ukombozi')}}</th>
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
						{{ Form::bulkActionGroup($item->id, $item->contact->name) }}
					</td>
					<td class=""><a href="{{route('church.pastors.show',$item->id)}}">{{ $item->contact->name }}</a></td>
					<td class="">{{$item->ukumbozi_number}}</td>
					<td class="">{{ Form::enabledGroup($item->id, $item->name, $item->enabled) }}</td>
					<td class="">{{$item->contact->phone}}</td>
					<td class="">{{$item->gender}}</td>

					<td class="">
						<div class="dropdown">
							<a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fa fa-ellipsis-h text-muted"></i>
							</a>

							<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

								<a class="dropdown-item" href="{{ route('church.pastors.edit', $item->id) }}">{{ trans('general.edit') }}</a>
								<!-- <a class="dropdown-item" href="{{ route('customers.duplicate', $item->id) }}">{{ trans('general.duplicate') }}</a> -->
								{!! Form::deleteLink($item, 'customers.destroy') !!}

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
<script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script>
@endpush