<tr>
	<td>{{ array_get($item, "ClientRefNum") }}</td>
	<td>{{ array_get($item, "InsuredName") }}</td>
	<td>{{ array_get($item, "InsurableBusiness.TradingName") }}</td>
	<td>@dateFormat(array_get($item, "ModifiedDateTime"))</td>
</tr>