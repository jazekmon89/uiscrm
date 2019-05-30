<table class="table">
	<caption>{{ 'Results for ' . $type }}</caption>
	<thead>
		<tr>
			<th>Insured Name</th>
			<th>Policy Name</th>
			<th>Date Submitted</th>
			<th>Reference No.</th>
		</tr>
	</thead>
	<tbody>
		@each("Search.Displays.RFQ.item", $items, 'item')
	</tbody>	
</table>