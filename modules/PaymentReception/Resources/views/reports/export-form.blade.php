<table>
	<tr>
		<td colspan="3">
			{{ $title }}
		</td>
		<td colspan="3">{{ \Carbon\Carbon::now()->format('d, F Y') }}</td>
	</tr>
</table>
<table>
	<thead>
		<th>Name</th>
		<th colspan="2" style="text-align: center">Amount to Pay</th>
		<th>Amount Paid</th>
		<th>Cheque Number</th>
		<th>Date Paid</th>
	</thead>
</table>
<table>
	<tbody>
		@foreach($data['regions'] as $d)
			<tr>
				<td>{{ $d->region->name ?? '-' }} {{ $d->region->type ?? '-' }}</td>
				<td></td>
				<td>{{ $d->amount }}</td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			@foreach ($data['sub_regions'][$d->regional_id] ?? [] as $sd)
				<tr>
					<td>{{ $sd->subregion->name ?? '-' }} {{ $sd->subregion->type ?? '-' }}</td>
					<td>{{ $sd->amount }}</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			@endforeach
		@endforeach
		@foreach ($data['orphan_subs'] as $reg=>$subs)
			<tr>
				<td>{{ $reg }}</td>
				<td></td>
				<td>-</td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			@foreach ($subs as $sd)
				<tr>
					<td>{{ $sd->subregion->name ?? '-' }} {{ $sd->subregion->type ?? '-' }}</td>
					<td>{{ $sd->amount }}</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>		
			@endforeach
		@endforeach
		<tr>
			<td>Pastor's Fund</td>
			<td></td>
			<td>{{ $data['pastors_fund']->amount }}</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>DCS</td>
			<td></td>
			<td>{{ $data['dc_secretariate']->amount }}</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Contribution to HQ</td>
			<td></td>
			<td>{{ $data['contribution_to_hq']->amount }}</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</tbody>
</table>