<table class="table">
	<caption>{{ 'Results for ' . $type }}</caption>
	<thead>
		<tr>
			<th>Client Referrence Number</th>
			<th>Insured Name</th>
			<th>Business</th>
			<th>Date Created</th>
		</tr>
	</thead>
	<tbody>
		@each("Search.Displays.Client.item", $items, 'item')
	</tbody>	
</table>