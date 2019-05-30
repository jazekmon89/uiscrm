<table class="table">
	<caption>{{ 'Results for ' . $type }}</caption>
	<thead>
		<tr>
			<th>Date Submitted</th>
			<th>Policy No.</th>
			<th>Insured Name</th>
			<th>Cover Start Date</th>
			<th>Cover End Date</th>
			<th>Underwriter</th>
			<th>Product</th>
			<th>Premium</th>
		</tr>
	</thead>
	<tbody>
		@each("Search.Displays.InsurancePolicy.item", $items, 'item')
	</tbody>	
</table>