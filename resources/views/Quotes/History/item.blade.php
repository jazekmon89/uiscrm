<tr 
	data-rfq='{{ json_encode($rfq) }}' 
	>
	<td>{{ $rfq['RFQ']['RFQRefNum'] }}</td>
	<td>{{ $rfq['RFQ']['LodgementDateTime'] }}</td>
	<td>{{ array_get($rfq, 'Organisation.Name') }}</td>
	<td>{{ array_get($rfq, 'PolicyType.Name') }}</td>
	<td>{{ array_get($rfq, 'Lead.Name') }}</td>
	<td>N/A</td>
	<td>N/A</td>
	<td>N/A</td>
</tr>