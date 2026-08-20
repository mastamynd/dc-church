@extends('layouts.admin')

@section('title', 'Payment Division List for cheque '.$cheque_number)
@section('content')
@if ($paymentList->count() || request()->get('search', false))
<span class="btn btn-success btn-sm" onclick="exportTableToCSV('cheque-{{ $cheque_number }}-distribution.csv')">Export CSV</span>
<div class="card">
	<div class="table-responsive custom">
		<table class="table table-flush table-hover">
			<thead class="thead-light">
				<tr class="table-head-line">
					<th>Church</th>
					<th>Pastor</th>
					<th>ID/No</th>
					<th>Region</th>
					<th>Sub Region</th>
					<th>Amount</th>
					<th>Cheque Number</th>
					<th>Month</th>
					<th>Year</th>
				</tr>
			</thead>
			<tbody>
				@php($amount = 0)
				@foreach ($paymentList as $item)
				@php($amount += floatval($item->amount))
				<tr class="align-items-center border-top-1">
					<td>{{ $item->church->name ?? '-' }}</td>
					<td>{{ $item->pastor ? $item->pastor->contact->name : $item->name }}</td>
					<td>{{ $item->pastor ? $item->pastor->id_or_passport : '-' }}</td>
					<td>{{ $item->region->name ?? '-' }}</td>
					<td>{{ $item->subregion->name ?? '-' }}</td>
					<td>{{ $item->amount }}</td>
					<td>{{ $item->cheque_number }}</td>
					<td>{{ $item->month }}</td>
					<td>{{ $item->year }}</td>
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4" style="text-align: right"><b>TOTAL</b></td>
					<td><b>@money($amount, setting('default.currency'), true)</b></td>
					<td></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
@else
<x-empty-page group="banking" page="transfers" textPage="{{ trans('demarcations::general.tithe') }}"
	routeCreate="{{ 'payment-reception.receive-tithe' }}"
	textEmptyPage="{{ trans('demarcations::general.no_records') }}" />
@endif
@endsection

@push('scripts_start')
<script src="{{ asset('public/js/sales/customers.js?v=' . version('short')) }}"></script>
<script>
	function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV file
    csvFile = new Blob([csv], {type: "text/csv"});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Hide download link
    downloadLink.style.display = "none";

    // Add the link to DOM
    document.body.appendChild(downloadLink);

    // Click download link
    downloadLink.click();
}

	function exportTableToCSV(filename) {
			var csv = [];
			var rows = document.querySelectorAll("table tr");
			
			for (var i = 0; i < rows.length; i++) {
					var row = [], cols = rows[i].querySelectorAll("td, th");
					
					for (var j = 0; j < cols.length; j++) {
							// Replace any commas with a space to avoid breaking the CSV format
							var cellValue = cols[j].innerText.replace(/,/g, " ");
							row.push('"' + cellValue + '"');
					}
					
					csv.push(row.join(","));        
			}

			// Download CSV file
			downloadCSV(csv.join("\n"), filename);
	}
</script>
@endpush