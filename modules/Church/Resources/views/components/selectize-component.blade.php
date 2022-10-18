@php($time = rand (10,100))

<div class="form-group" id="{{ $time }}-id">
	<label for="name" class="form-control-label">{{ $label }}</label>
	<div class="input-group input-group-merge bg-grey">
		<div class="input-group-prepend">
			<span class="input-group-text">
				<i class="fas fa-{{ isset($config['icon']) ? $config['icon'] : 'font' }}"></i>
			</span>
		</div>
		<select {!! $url ? 'data-url="'.$url.'/"' :'' !!} {{ $required }} name="{{ $config['name'] }}" 
			data-valuefield="{{ $config['valueField'] ?? 'id' }}" data-searchfield="{{ $config['searchField'] ?? 'name' }}" data-labelfield="{{ $config['labelField'] ?? 'name' }}" placeholder="{{ $config['placeholder'] ?? 'Select...' }}" 
			class="form-control selectize-field-2 form-control-lg {{ implode(' ', $config['classes'] ?? []) }}" {{ $multiple ? 'multiple' : '' }}>
			<option value="">{{ $config['placeholder'] ?? 'Select...' }}</option>
				@foreach ($config['options'] ?? [] as $item)
					@php($item = (array) $item)
					@if(!$multiple)
						<option value="{{ $noKey ? $item : $item[$config['valueField'] ?? 'id'] }}" {{ $selected == ($noKey ? $item : $item[$config['valueField']]) ? 'selected' : '' }}>{{ $noKey ? $item : ($item[$config['labelField'] ?? 'name']) }}</option>
					@else
						<option value="{{ $noKey ? $item : $item[$config['valueField'] ?? 'id'] }}" {{ in_array(($noKey ? $item : $item[$config['valueField']]), $selected) ? 'selected' : '' }}>{{ $noKey ? $item : ($item[$config['labelField'] ?? 'name']) }}</option>
					@endif
				@endforeach
		</select>
	</div>
</div>

@push('scripts_start')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css" integrity="sha512-bkB9w//jjNUnYbUpATZQCJu2khobZXvLP5GZ8jhltg7P/dghIrTaSJ7B/zdlBUT0W/LXGZ7FfCIqNvXjWKqCYA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.bootstrap4.min.css" integrity="sha512-MMojOrCQrqLg4Iarid2YMYyZ7pzjPeXKRvhW9nZqLo6kPBBTuvNET9DBVWptAo/Q20Fy11EIHM5ig4WlIrJfQw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js" integrity="sha512-pF+DNRwavWMukUv/LyzDyDMn8U2uvqYQdJN0Zvilr6DDo/56xPDZdDoyPDYZRSL4aOKO/FGKXTpzDyQJ8je8Qw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>	
	<script>
		var triggerSelectize = function(tis){
			var $url = $(tis).data('url');
			var $valueField = $(tis).data('valuefield');
			var $labelField = $(tis).data('labelfield');
			var $searchField = $(tis).data('searchfield');
			var $icon = $(tis).data('icon');
			var locked = $(this).attr('readonly') == 'true';

			console.log(tis, $url, $labelField, $searchField, $valueField);

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
			$('#{{ $time }}-id').find('select').each(function(){
				if($(this)[0].selectize) {
					$(this)[0].selectize.destroy();
				}
				triggerSelectize(this);
			});
		});
	</script>
@endpush