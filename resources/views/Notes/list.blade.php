<table>
	<thead>
		<th>Date Created</th>
		<th>Created By</th>
		<th>Description</th>
	</thead>
		<tr><td></td></tr>
		@if(isset($notes))
			@foreach($notes as $note)
				<tr><td>{{ $note->DateCreated }}</td></tr>
				<tr><td>{{ $note->CreatedBy }}</td></tr>
				<tr><td><textarea>{{ $note->Description }}</textarea></td></tr>
			@endforeach
		@endif
	<tbody>
	</tbody>
</table>