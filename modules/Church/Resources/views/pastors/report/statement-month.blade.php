@extends('layouts.admin')
@php($time = time())
@section('title', trans_choice('church::general.pastor_statement', 2))

@section('content')

<div class="card">
	<div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
		<div class="row" id="select-form">
			<div class="col-sm-4 col-md-3 col-lg-2">
				<div class="form-group">
					{{-- <label class="control-label">{{ trans_choice('church::general.year', 1) }}</label> --}}
					<div class="input-group input-group-merge ">
						<div class="input-group-prepend">
							<span class="input-group-text">
								<i class="fa fa-calendar"></i>
							</span>
						</div>
						<select class="form-control selectize-field" name="year" id="year-filter">
							@foreach($years as $y)
								<option value="{{ $y }}" {{ intval($y)==intval($year) ? 'selected' : '' }}>{{ $y }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-3 col-lg-3">
				<div class="form-group">
					{{-- <label class="control-label">{{ trans_choice('church::general.month', 1) }}</label> --}}
					<div class="input-group input-group-merge ">
						<div class="input-group-prepend">
							<span class="input-group-text">
								<i class="fa fa-calendar"></i>
							</span>
						</div>
						<select class="form-control selectize-field" name="month" id="month-filter">
							<option value="all">All</option>
							@foreach($months as $m)
								<option value="{{ $m }}" {{ $m==$month ? 'selected' : '' }}>{{ ucfirst($m) }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-3 col-lg-3">
				<div class="form-group form-group-lg">
					{{-- <label class="control-label">{{ trans_choice('church::general.pastor', 1) }}</label> --}}
					<div class="input-group input-group-merge ">
						<div class="input-group-prepend">
							<span class="input-group-text">
								<i class="fa fa-user"></i>
							</span>
						</div>
						<select data-url="{{ route('church.fetch_pastors') }}/" name="pastor_id" id="pastor-filter"
							placeholder="{{ trans_choice('church::general.pastor', 1) }}..."
							class="form-control selectize-field" data-searchfield="name" data-labelfield="name" data-valuefield="id">
							@if ($pastor)
								<option value="{{ $pastor->id }}" selected> {{ $pastor->name }}</option>
							@endif
						</select>
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-3 col-lg-3">
				<div class="form-group form-group-lg">
					{{-- <label class="control-label">{{ trans('church::general.name') }}</label> --}}
					<div class="input-group input-group-merge ">
						<div class="input-group-prepend">
							<span class="input-group-text">
								<i class="fas fa-solid fa-church"></i>                                
							</span>
						</div>
						<select data-url="{{ route('church.get_church') }}/" id="church-filter" name="church_id" placeholder="{{ trans('church::general.name') }}..." class="form-control selectize-field" data-searchfield="name" data-labelfield="name" data-valuefield="id">
							@if ($church)
								<option value="{{ $church->id }}" selected> {{ $church->name }}</option>
							@endif
						</select>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-6 col-lg-1">
				<div class="form-group">
					<button class="btn btn-info btn-block" id="trigger-search">
						<i class="fas fa-search"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
	<div class="table-responsive custom">
		@if (!$month)
			<table class="table table-flush table-hover">
				<thead class="thead-light">
					<tr class="table-head-line">
						<th>Name</th>
						@foreach ($months as $m)
							<th>{{ strtoupper($m) }}</th>
						@endforeach
					</tr>
				</thead>
				<tbody>
					@php($total = [])
					@foreach ($statements as $item)
						@php($itm = (array) $item)
						<tr>
							<th>{{ $item->name }}</th>
							@foreach ($months as $m)
								@php(!isset($total[$m]) ? $total[$m] = 0 : null)
								@php($total[$m]+=floatval($item->$m ?? 0))
								<td class="text-right">@money($item->$m, 'KES', true)</td>
							@endforeach
						</tr>
					@endforeach
				</tbody>
				<tfoot>
					<td></td>
					@foreach ($months as $m)
						<th class="text-right"><a href="{{ route('church.pastors.statements', [$year, $m]) }}">@money($total[$m], 'KES', true)</a></th>
						@endforeach
				</tfoot>
			</table>
		@else
			<table class="table table-flush table-hover">
				<thead class="thead-light">
					<tr class="table-head-line">
						<th>NO</th>
						<th>{{ trans('church::general.name') }}</th>
						<th>{{ trans_choice('church::general.pastor', 1) }}</th>
						<th>{{ trans('general.amount') }}</th>
						<th>{{ trans('general.phone') }}</th>
						<th>{{ trans('church::general.passport') }}</th>
						<th>{{ trans('church::general.ukombozi') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($statements as $item)
						<tr>
							<td>{{ $loop->iteration }}</td>
							<td>{{ $item->church }}</td>
							<td>{{ $item->pastor ?? '-' }}</td>
							<td>@money($item->amount, 'KES', true)</td>
							<td>{{ $item->phone ?? '-' }}</td>
							<td>{{ $item->id_or_passport ?? '-' }}</td>
							<td>{{ $item->ukombozi_number ?? '-' }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@endif
	</div>
	<div class="card-footer table-action">
		@if ($month)
			<div class="row">
				@include('partials.admin.pagination', ['items' => $statements])
			</div>
		@endif
	</div>
</div>
@endsection

@push('scripts_start')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css" integrity="sha512-bkB9w//jjNUnYbUpATZQCJu2khobZXvLP5GZ8jhltg7P/dghIrTaSJ7B/zdlBUT0W/LXGZ7FfCIqNvXjWKqCYA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.bootstrap4.min.css" integrity="sha512-MMojOrCQrqLg4Iarid2YMYyZ7pzjPeXKRvhW9nZqLo6kPBBTuvNET9DBVWptAo/Q20Fy11EIHM5ig4WlIrJfQw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js" integrity="sha512-pF+DNRwavWMukUv/LyzDyDMn8U2uvqYQdJN0Zvilr6DDo/56xPDZdDoyPDYZRSL4aOKO/FGKXTpzDyQJ8je8Qw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>	
<script type="text/javascript">
	var triggerSelectize = function(tis){
		var $url = $(tis).data('url');
		var $valueField = $(tis).data('valuefield');
		var $labelField = $(tis).data('labelfield');
		var $searchField = $(tis).data('searchfield');
		var $icon = $(tis).data('icon');
		var locked = $(this).attr('readonly') == 'true';

		if($url){
			$(tis).selectize({
				valueField: $valueField,
				labelField: $labelField,
				searchField: $searchField,
				create: false,
				render: {
					option: function (item, escape) {
						console.log(item);
						return (
							`<div class="p-2"><i class="far ${$icon}"></i> ${item[$labelField]}</div>`
						);
					},
				},
				load: function (query, callback) {
					if (!query.length) return callback();
					$.ajax({
						url: $url + encodeURIComponent(query),
						type: "GET",
						error: function () {
							callback();
						},
						success: function (res) {
							callback(res);
						},
					});
				},
			});
		}
		else{
			$(tis).selectize();
		}

		if(locked){
			tis[0].selectize.lock();
		}
	}

	$(function(){
		$('#select-form').find('select').each(function(){
			if($(this)[0].selectize) {
				$(this)[0].selectize.destroy();
			}
			triggerSelectize(this);
		});

		$('#trigger-search').on('click', function(){
			var $year = $('#year-filter').val();
			var $month = $('#month-filter').val();
			var $church = $('#church-filter').val();
			var $pastor = $('#pastor-filter').val();
			if($.trim($month)!=="" && $month != 'all'){
				var route = '{{ route("church.pastors.statements") }}/'+$year+'/'+$month;
			}
			else{
				var route = '{{ route("church.pastors.statements") }}/'+$year;
			}

			var params = [];

			if($.trim($church) !=""){
				params.push('church_id='+$church);
			}

			if($.trim($pastor) !=""){
				params.push('pastor_id='+$pastor);
			}

			window.location = route+'?'+params.join('&');
		})
	});
</script>
<!-- <script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script> -->
@endpush