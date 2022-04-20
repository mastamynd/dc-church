<table style="border: none; border-collapse: collapse; font-family: Arial; width: 100%">
	<tr style="width: 100%">
		<td colspan="1" style="width: 1%">
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
		<td nowrap style="width: 1%; white-space: nowrap;">
			<div style="padding: 10px 0px 3px">
				<strong>Receipt: </strong><span style="font-family: monospace">{{ $receipt->number }}</span>
			</div>
			<div style="padding: 3px 0px">
				<strong>Date: </strong> <span style="font-family: monospace; padding: 5px 0px">{{ $receipt->recon->receipt_date }}</span>
			</div>
		</td>
	</tr>
</table>
<hr color="#ccc">
<table style="border: none; border-collapse: collapse; font-family: Arial; width: 100%">
	<tr style="width: 100%">
		<td style="width: 1%; white-space: nowrap;" nowrap>
			<span>
				<strong>Account No </strong>
			</span>
			<br><br>
			<span>
				<strong>Received From </strong> 
			</span>
			<br><br>
		</td>
		<td nowrap style="width: 98%; white-space: nowrap;">
			<span style="font-family: monospace; padding: 3px 0px 3px 15px">: {{ $receipt->customer->reference ?? '' }}</span><br><br>
			<span style="font-family: monospace; padding: 3px 0px 3px 15px">: {{ $receipt->customer->name ?? '' }}</span><br><br>
		</td>
		<td nowrap style="width: 1%;  white-space: nowrap;">
			<strong>YEAR : </strong> <span style="font-family: monospace; padding: 3px 0px">{{ $receipt->class ?? '' }}</span>
		</td>
	</tr>
</table>
<br>
<table style="border: none; border-collapse: collapse; font-family: Arial; width: 100%">
	<tr style="width: 100%">
		<td colspan="10">
			@php($f = new NumberFormatter("en", NumberFormatter::SPELLOUT))
			@php($amount_in_words = $f->format(floatval($receipt->amount)))
			The sum of <span style="font-family: monospace; padding: 10px 3px !important; border-bottom: 1px solid #fff !important">
				{{ $receipt->currency ?? 'Ksh' }} {{ $amount_in_words }}
			</span>
		</td>
	</tr>
</table>
<br><br>
<table style="border: none; border-collapse: collapse; font-family: Arial; width: 100%">
	<tr style="width: 100%; border-bottom: 3px solid #22375a">
		<th style="padding-bottom: 10px; width: 60%">
			<strong>Payment</strong>
		</th>
		<th style="padding-bottom: 10px; width: 40%">
			<strong style="text-align: right">Amount</strong>
		</th>
	</tr>
	@foreach($receipt->recon->consensus_items ?? [] as $item)
		<tr style="font-family: monospace; width: 100%; border-bottom: 3px solid #22375a">
			<td style="padding-left: 15px; padding-top: 5px; padding-bottom: 10px; width: 60%">{{ $item->item_name }}</td>
			<td style="padding-bottom: 10px; width: 40%; text-align:right">{{ $item->payable_amount }}</td>
		</tr>
	@endforeach
</table>
<table style="border: none; border-collapse: collapse; font-family: Arial; width: 100%">
	<tr style="width: 100%">
		<td style="width: 60%">&nbsp;</td>
		<td style="width: 20%; padding:5px; border-bottom: 3px solid #22375a">&nbsp;</td>
		<td style="width: 20%; padding:5px; font-family: monospace; border-bottom: 3px solid #22375a; text-align: right">{{ number_format(floatval($receipt->amount),2) }}</td>
	</tr>
	<tr style="width: 100%">
		<td style="width: 60%">&nbsp;</td>
		<td style="width: 20%; padding:5px; border-bottom: 3px solid #22375a"><strong>TOTAL</strong></td>
		<td style="width: 20%; padding:5px; font-family: monospace; border-bottom: 3px solid #22375a; text-align: right">{{ number_format(floatval($receipt->amount),2) }}</td>
	</tr>
</table>
<br>

<hr color="#ccc">
<br><br>
<table style="border: none; border-collapse: collapse; font-family: Arial; width: 100%">
	<tr>
		<td style="width:45%; text-align:right">
			Payment Mode : {{ $receipt->mode_of_payment }}
		</td>
		<td style="width:10%">&nbsp;</td>
		<td style="width:45%; tect-align:left">
			{{ $receipt->recon->account->name ?? ($receipt->mode_of_payment == "cash" ? 'Cash' : '---') }}
		</td>
	</tr>
</table>
<br><br>
<table style="border: none; border-collapse: collapse; font-family: Arial; width: 100%">
	<tr>
		<td style="width: 50%">You were served by <strong>{{ $receipt->creator->name }}</strong></td>
		<td style="width: 50%; text-align:right">Served On {{ \Carbon\Carbon::parse($receipt->created_at)->format('F, jS Y \@ H:i') }}</td>
	</tr>
</table>
<script>
	window.onload = window.print();
</script>