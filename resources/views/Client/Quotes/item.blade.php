<tr 
	data-quote='{{ json_encode($quote) }}' 
	data-expire-link="{{ route('client.quotes.expire', [array_get($quote, 'ClientID'), $quote['InsuranceQuoteID']]) }}"
	data-finalize-link="{{ route('client.quotes.finalize', [array_get($quote, 'ClientID'), $quote['InsuranceQuoteID']]) }}"
	>
	<td>{{ Form::checkbox("QuoteID[]", $quote["InsuranceQuoteID"], false) }}</td>
	<td>Status</td>
	<td>{{ $quote['CreatedDateTime'] ? $quote['CreatedDateTime']->format('m/d/Y') : '' }}</td>
	<td>{{ $quote['QuoteNum'] }}</td>
	<td>{{ array_get($quote, 'Client.InsuredName') }}</td>
	<td>{{ $quote['CoverStartDateTime'] ? $quote['CoverStartDateTime']->format('m/d/Y') : '' }}</td>
	<td>{{ $quote['CoverEndDateTime'] ? $quote['CoverEndDateTime']->format('m/d/Y') : '' }}</td>
	<td>{{ array_get($quote, 'Underwriter.CompanyName') }}</td>
	<td>{{ array_get($quote, 'Product') }}</td>
	<td>{{ array_get($quote, 'Premium') }}</td>
</tr>