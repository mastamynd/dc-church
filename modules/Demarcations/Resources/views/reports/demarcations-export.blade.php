<table>
	<thead class="thead-light">
		<tr class="table-head-line">
			<th>#</th>
			<th>Name</th>
			<th>Code</th>
			<th class="text-right">{{ $type == 'region' ? 'Sub Regions' : 'Churches' }}</th>
			<th>{{ trans('Overseer') }}</th>
			<th>{{ trans('Contact') }}</th>
		</tr>
	</thead>

	<tbody>
		@foreach($demarcations as $item)
			<tr class="align-items-center border-top-1">
				<td>
					{{ $loop->iteration }}
				</td>
				<td>
					<a class="long-texts" href="{{ route('demarcations.show', [$type, $item->id]) }}">{{ $item->name }}</a>
				</td>
				<td>
					<a class="long-texts" href="{{ route('demarcations.show', [$type, $item->id]) }}">{{ $item->code }}</a>
				</td>
				<td class="text-right">
					{{ $item->children->count() }}
				</td>
				<td class="">{{ $item->dname ?? '-' }}</td>
				<td class="">{{ $item->phone ?? '-' }}</td>
			</tr>
		@endforeach
	</tbody>
</table>