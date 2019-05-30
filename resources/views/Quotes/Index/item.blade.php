<tr 
	data-quote='{{ json_encode($quote) }}' 
	data-expire-link="{{ route('quotes.expire', [array_get($quote, 'Quote.OrganisationID'), $quote['InsuranceQuoteID']]) }}"
	data-finalize-link="{{ route('quotes.finalize', [array_get($quote, 'Quote.OrganisationID'), $quote['InsuranceQuoteID']]) }}"
	>
	<td>{{ Form::checkbox("QuoteID[]", $quote["InsuranceQuoteID"], false) }}</td>
	<td>@dateFormat(array_get($quote, 'CreatedDateTime'))</td>
	<td>{{ array_get($quote, 'QuoteNum') }}</td>
	<td>{{ array_get($quote, 'Client.InsuredName') }}</td>
	<td>@dateFormat(array_get($quote, 'CoverStartDateTime'))</td>
	<td>@dateFormat(array_get($quote, 'CoverEndDateTime'))</td>
	<td>{{ array_get($quote, 'Underwriter.CompanyName') }}</td>
	<td>{{ array_get($quote, 'Product') }}</td>
	<td>{{ array_get($quote, 'Premium') }}</td>
</tr>