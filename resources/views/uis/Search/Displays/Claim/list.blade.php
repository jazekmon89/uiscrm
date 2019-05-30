<table class="table">
	<caption>{{ 'Results for ' . $type }}</caption>
	<thead>
		<tr>
			<th>Policy No.</th>
			<th>Claim No.</th>
			<th>Date submitted</th>
			<th>Contact</th>
			<th>PhoneNumber</th>
		</tr>
	</thead>
	<tbody>
		@each("Search.Displays.Contact.item", $items, 'item')
	</tbody>	
</table>