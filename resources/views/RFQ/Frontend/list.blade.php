<table class="table table-condensed table-hover table-striped {{ $type }} rfqs">
	<thead>
		<tr>
			<td>Reference Number</td>
			<td>Date Lodge</td>
			<td>Insurance Company</td>
			<td>Policy Name</td>
			<td>Consultant Name</td>
			<td>Status</td>
			<td>Outstanding</td>
			<td>Claimed Amount</td>
		</tr>	
	</thead>
	<tbody>
		@if($rfqs)
			@foreach($rfqs as $RFQ)
				<tr 
					data-lodge-url="{{ route('quotes.form', [array_get($RFQ, 'PolicyTypeID'), array_get($RFQ, 'PolicyType.FormTypeID')]) }}"
					data-view-url="{{ route('user.rfqs', [array_get($RFQ, 'RFQID')]) }}"
					>
					<td>{{ array_get($RFQ, "RFQRefNum") }}</td>
					<td>@dateFormat(array_get($RFQ, "LodgementDateTime"))</td>
					<td>{{ array_get($RFQ, "PreviousClaim.InsurerCompanyName") }}</td>
					<td>{{ array_get($RFQ, "PolicyType.DisplayText") }}</td>
					<td></td>
					<td>{{ array_get($RFQ, "RFQStatus.Name") }}</td>
					<td>{{ (double)array_get($RFQ, "PreviousClaim.AmountOutstanding", 0) }}</td>
					<td>{{ array_get($RFQ, "PreviousClaim.AmountPaid") }}</td>
					<td>{{ array_get($RFQ, "TotalAmountPaid") }}</td>
				</tr>
			@endforeach
		@endif
		<tr class="empty {{ $rfqs ? 'hidden' : '' }}"><td colspan="10" class="text-center">No entries found.</td></tr>
	</tbody>
</table>