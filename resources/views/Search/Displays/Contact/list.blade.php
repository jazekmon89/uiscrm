<table class="table">
	<caption>{{ 'Results for ' . $type }}</caption>
	<thead>
		<tr>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Email Address</th>
			<th>BirthDate</th>
		</tr>
	</thead>
	<tbody>
		@each("Search.Displays.Contact.item", $items, 'item')
	</tbody>	
</table>