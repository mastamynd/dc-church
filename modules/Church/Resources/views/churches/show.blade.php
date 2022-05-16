@extends('layouts.admin')

@section('title', $church->name)

@section('new_button')
	@stack('edit_button_start')
	@can('update-sales-customers')
		<a href="{{ route('church.edit', $church->id) }}" class="btn btn-white btn-sm">
			{{ trans('general.edit') }}
		</a>
	@endcan
	@stack('edit_button_end')
@endsection

@section('content')
	<div class="row">
		<div class="col-xl-3">
			{{-- <ul class="list-group mb-4">
				@stack('customer_invoices_count_start')
				<li class="list-group-item d-flex justify-content-between align-items-center border-0">
					{{ trans_choice('general.invoices', 2) }}
					<span class="badge badge-primary badge-pill">{{ $counts['invoices'] }}</span>
				</li>
				@stack('customer_invoices_count_end')

				@stack('customer_transactions_count_start')
				<li class="list-group-item d-flex justify-content-between align-items-center border-0 border-top-1">
					{{ trans_choice('general.transactions', 2) }}
					<span class="badge badge-primary badge-pill">{{ $counts['transactions'] }}</span>
				</li>
				@stack('customer_transactions_count_end')
			</ul> --}}

			<ul class="list-group mb-4">
				@stack('church_image_start')
				<li class="list-group-item border-0">
					<div class="font-weight-600">{{ trans('general.name') }}</div>
					<div class="p-2">
						<img src="{{ $church->photo }}" class="w-100" />	
					</div>
				</li>
				@stack('church_image_end')

				@stack('church_name_start')
				<li class="list-group-item border-0 border-top-1">
					<div class="font-weight-600">{{ trans('general.name') }}</div>
					<div><small class="long-texts" title="{{ $church->name }}">{{ $church->name }}</small></div>
				</li>
				@stack('church_name_end')

				@stack('church_code_start')
				<li class="list-group-item border-0 border-top-1">
					<div class="font-weight-600">{{ trans('church::general.code') }}</div>
					<div><small class="long-texts" title="{{ $church->code }}">{{ $church->code }}</small></div>
				</li>
				@stack('church_code_end')

				@stack('church_region_start')
				<li class="list-group-item border-0 border-top-1">
					<div class="font-weight-600">{{ trans_choice('church::general.region', 1) }}</div>
					<div>
						@if(isset($church->region) && isset($church->region->demarcation))
							<a class="long-texts" href="{{ route('church.show', $church->region->demarcation->id) }}">{{ $church->region->demarcation->name }}</a>
						@else
							<span class="badge badge-warning badge-sm">Not Set</span>
						@endif
					</div>
				</li>
				@stack('church_region_end')

				@stack('church_subregion_start')
				<li class="list-group-item border-0 border-top-1">
					<div class="font-weight-600">{{ trans_choice('church::general.subregion', 1) }}</div>
					<div>
						@if(isset($church->subregion) && isset($church->subregion->demarcation))
							<a class="long-texts" href="{{ route('church.show', $church->subregion->demarcation->id) }}">{{ $church->subregion->demarcation->name }}</a>
						@else
							<span class="badge badge-warning badge-sm">Not Set</span>
						@endif
					</div>
				</li>
				@stack('church_subregion_end')
			</ul>
			@stack('customer_edit_button_start')
			@stack('customer_edit_button_end')
		</div>
		<div class="col-xl-9"></div>
	</div>
@endsection

@push('scripts_start')
	{{-- <script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script> --}}
@endpush
