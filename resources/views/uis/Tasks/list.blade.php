@if(!empty($tasks))
	@foreach($tasks as $i)
	<tr data-id='{{ $i->TaskID }}'>
		<td><input type='checkbox'></td>
		<td>{{ $i->TaskType}}
		<td>{{ $i->Subject }}</td>
		<td>{{ $i->Description }}</td>
		<td>{{ $i->Assigned }}</td>
		<td>{{ $i->DueDate }}</td>
		<td>{{ $i->Status }}</td>
		<td>{{ $i->CreatedDate }}</td>
		@if($can_update)
		<td class="task-update-cell">
			<a class='task-edit' data-pid="{{ $i->TaskID }}" data-all="{{ json_encode($i) }}" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>
			<a class='task-delete' data-pid="{{ $i->TaskID }}" title="Delete"><span class="glyphicon glyphicon-remove-circle"></span></a>
		</td>
		@endif
	</tr>
	@endforeach
	<input type="hidden" id="taskPID" value='{{ $ParentID }}'>
@else
<tr>
	<td colspan={{ $can_update?9:8 }} style="text-align:center;">No data.</td>
</tr>
@endif