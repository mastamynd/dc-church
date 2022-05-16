<style type="text/css">
	*{
		font-family: 'Times New Roman', Times, serif;	
	}
	td, th{
		padding: 4px 5px;
		font-size: 12px;
	}

	table, td, th{
		border-color: #565656;
	}

	table{
		color: #232323;
		border-spacing: 5px 0px;
	}
</style>
<table style="border: none; border-collapse: collapse; font-family: Arial; width: 100%">
	<tr style="width: 100%">
		<td colspan="1" style="width: 2%">
			<div style="padding"  style="padding: 5px 5px 5px 0px">
				<img src="{{ asset('/public/img/logo.png') }}" style="max-width: 200px; margin: 0px 15px 5px 0px" />
			</div>
		</td>
		<td style="width: 98%">
			<div style="padding:5px;">
				<strong style="font-size: 17px; padding: 4px 0px">{{ setting('company.name') }}</strong>
				<br>
				<span style="font-size: 14px; padding: 4px 0px">{{ setting('company.address') }}</span>
				<br>
				<span style="font-size: 14px; padding: 4px 0px">{{ setting('company.phone') }}</span>
			</div>
		</td>
	</tr>
</table>
<br><br>
<hr color="#ccc">
<h1 style="width: 100%; text-align: center; color: rgb(0, 45, 191)">STATEMENT</h1>

<table style="border: none; border-collapse: collapse; font-family: Arial; width: 100%">
	<td style="width: 100%; font-style: italic; font-weight: 400">
		For period between {{ $start_date }} and {{ $end_date }}.
	</td>
</table>
<br>
<table style="border: none; border-collapse: collapse; font-family: Arial; width: 100%">
	<td style="width: 1%" nowrap><strong>To:</strong></td>
	<td style="width: 70%; padding-left: 10px">
		<pre style="font-family: inherit; font-size: inherit">{{ $customer->address }}</pre>
	</td>
	<td style="width: 29%" nowrap>
		Student: {{ $customer->name }}
		<br><br>
		Acc/No: {{ $customer->reference }}
		<br><br>
		Class: {{ $customer->class }}
	</td>
</table>
<br>
<br>
<table style="border: none; font-family: Arial; width: 100%; text-align:center">
	<thead>
		<th>No</th>
		<th>Description</th>
		<th>Date</th>
		<th>Ref Number</th>
		<th>Currency</th>
		<th style="text-align: right">Debit</th>
		<th style="text-align: right">Credit</th>
		<th style="text-align: right">Balance</th>
	</thead>
	<tbody>
		@php($dt = $data[0])
		@php($BALANCE = ($balanceBFTT->debit_total - $balanceBFTT->credit_total))
		@php($isNegative = $BALANCE < 0)
		<tr>
			<td>1</td>
			<td style="border: 1px solid"><strong>{{ $isNegative ? 'Balance C/F' : 'Balance B/F' }}</strong></td>
			<td style="border: 1px solid"></td>
			<td style="border: 1px solid"></td>
			<td style="border: 1px solid"></td>
			<td style="text-align: right;border: 1px solid">@money(!$isNegative ? $BALANCE : 0, setting('default.currency', 'KES'), true)</td>
			<td style="text-align: right;border: 1px solid">@money($isNegative ? $BALANCE : 0, setting('default.currency', 'KES'), true)</td>
			<td style="text-align: right;border: 1px solid">@money($BALANCE, setting('default.currency', 'KES'), true)</td>
		</tr>
		@foreach($data as $dt)
			<tr>
				<td>{{ $loop->iteration+1 }}</td>
				<th style="border: 1px solid; {{ floatval($dt->Debit) > 0 ? 'color:#333a40' : 'color:#33403b' }}">{{ $dt->Type }}</th>
				<td style="border: 1px solid">{{ $dt->Date }}</td>
				<td style="border: 1px solid">{{ $dt['Invoice Number'] }}</td>
				<td style="border: 1px solid">{{ setting('default.currency', 'KES') }}</td>
				<td style="text-align: right;border: 1px solid">@money($dt->Debit, setting('default.currency', 'KES'), false)</td>
				<td style="text-align: right;border: 1px solid">@money($dt->Credit, setting('default.currency', 'KES'), true)</td>
				<td style="text-align: right;border: 1px solid">@money($dt->row_balance, setting('default.currency', 'KES'), true)</td>
			</tr>
		@endforeach
	</tbody>
</table>
<br>
<table style="border: none; font-family: Arial; width: 100%; text-align:center">
	<tr>
		<td><strong>NOTE:</strong> Age Analysis is calculated based on the current date and is no way bound to the dates that the statement is bound to. Thus, the figures
		in the Age Analysis may differ from the ones provided in the statement.</td>
	</tr>
</table>
<br>
<h5>Age Analysis</h5>
<div style="display: flex; justify-content: space-between">
	@php($total_amnt = 0)
	@foreach ($ageAnalysis as $age)
		<div style="display: flex; flex-direction: column; text-align: center">
			<strong>{{ $age->title }}</strong>
			<br>
			<small>@money(abs($age->value), setting('default.currency', 'KES'), true)</small>
		</div>
	@endforeach
	<div style="display: flex; flex-direction: column; text-align: center">
		<strong>TOTAL</strong>
		<br>
		<small>{{ $total_amnt }}</small>
	</div>
</div>
<br>
@if ($dt->row_balance != 0)
<table style="border: none; border-collapse: collapse; font-family: Arial; width: 100%">
	<tr>
		<td style="text-align: center">
			<strong>
				Please note that this account is in {{ $dt->row_balance > 0 ? 'debit' : 'credit' }} by @money(abs($dt->row_balance), setting('default.currency', 'KES'), true)
			</strong>
		</td>
	</tr>
</table>
@endif
<script>
	window.onload = window.print();
</script>