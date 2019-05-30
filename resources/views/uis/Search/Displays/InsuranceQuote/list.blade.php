<table class="table">
	<caption>{{ 'Results for ' . $type }}</caption>
	<thead>
		<tr>
			<th>Date Submitted</th>
			<th>Quote No.</th>
			<th>Insured Name</th>
			<th>Cover Start Date</th>
			<th>Cover End Date</th>
			<th>Underwriter</th>
			<th>Product</th>
			<th>Premium</th>
		</tr>
	</thead>
	<tbody>
		@each("Search.Displays.InsuranceQuote.item", $items, 'item')
	</tbody>	
</table>