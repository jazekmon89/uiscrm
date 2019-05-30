<tr 
	data-claim='{{ json_encode($item) }}' 
	>
	<td>{{ $item['Reference_Number'] }}</td>
	<td>{{ $item['Date_Lodged'] }}</td>
	<td>{{ $item['Insurance_Company'] }}</td>
	<td>{{ $item['Insurance_Name'] }}</td>
	<td>{{ $item['Consultant_Name'] }}</td>
	<td>{{ $item['Status'] }}</td>
	<td>{{ $item['Excess'] }}</td>
	<td>{{ $item['Claimed_Amount'] }}</td>
</tr>