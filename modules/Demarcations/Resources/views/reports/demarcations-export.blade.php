<table>
	<thead class="thead-light">
		<tr class="table-head-line">
			<th>#</th>
			<th>Type</th>
			<th>Name</th>
			<th>Code</th>
			<th class="text-right">{{ $type == 'region' ? 'Sub Regions' : 'Churches' }}</th>
			<th>{{ trans_choice('demarcations::general.bank', 1) }}</th>
			<th>{{ trans('demarcations::general.paybill') }}</th>
			<th>{{ trans('demarcations::general.account_number') }}</th>
			<th>{{ trans('demarcations::general.branch_code') }}</th>
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
					{{ $item->type }}
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
				<td class="">{{ $item->bank_name ?? '-' }}</td>
				<td class="">{{ $item->paybill ?? '-' }}</td>
				<td class="">{{ $item->account_number ?? '-' }}</td>
				<td class="">{{ $item->branch_code ?? '-' }}</td>
				<td class="">{{ $item->dname ?? '-' }}</td>
				<td class="">{{ $item->phone ?? '-' }}</td>
			</tr>
		@endforeach
	</tbody>
</table>