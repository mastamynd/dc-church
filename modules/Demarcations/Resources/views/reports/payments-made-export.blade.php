<table>
	<thead class="thead-light">
		<tr class="table-head-line">
			<th>Church</th>
			<th>Type</th>
			<th>Name</th>
			<th>Payable</th>
			<th>Paid</th>
			<th>Pending</th>
			<th>Month</th>
			<th>Year</th>
			<th>Date</th>
			<th>Cheque</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($payments as $item)
			@php($payment_date = \Carbon\Carbon::parse($item->payment_date))
			<tr class="align-items-center border-top-1">
				<td>{{ $item->church_name }}</td>
				<td>{{ $item->type }}</td>
				<td>{{ $item->name }}</td>
				<td>{{ $item->to_pay }}</td>
				<td>{{ $item->paid }}</td>
				<td>{{ $item->to_pay-$item->paid }}</td>
				<td>{{ $item->month }}</td>
				<td>{{ $item->year }}</td>
				<td>{{ $item->payment_date }}</td>
				<td>{{ $item->cheque_number }}</td>
			</tr>
		@endforeach
	</tbody>
</table>