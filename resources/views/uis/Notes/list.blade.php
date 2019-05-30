@if(isset($notes) && count($notes))
	@foreach($notes as $note)
		<tr>
			<td>{{ strip_tags($note->Description) }}</td>
			<td>{{ $note->CreatedDateTime }}</td>
			<td>{{ $note->CreatedBy }}</td>
			@if($can_update)
			<td>
				@if($note->IsEditable)
				<a class='note-edit' data-nid="{{ $note->NoteID }}" data-all="{{ json_encode($note) }}" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>
				@endif
				<a class='note-delete' data-nid="{{ $note->NoteID }}" title="Delete"><span class="glyphicon glyphicon-remove-circle"></span></a>
			</td>
			@endif
		</tr>
	@endforeach
@else
	<tr><td colspan='3' style='text-align:center'>No data.</td></tr>
@endif