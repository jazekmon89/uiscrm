@if(!$hideAddNoteButton)
<div class="form-group">
	<div class="btn-group">
	  <button type="button" class="btn btn-default" data-toggle="modal" data-target="#new_note" id="add_note">Add New Note</button>
	</div>
</div>
@endif
@if(!$hideList)
<div class = "form-group">
	<table id="notesTable" class="table table-bordered table-striped">
		<thead>
			<th>Description</th>
			<th>Date Created</th>
			<th>Created By</th>
			@if($can_update)
			<th></th>
			@endif
		</thead>
		<tbody>
			{!! $notes_list !!}
		</tbody>
	</table>
</div>
<input type="hidden" value="{{ $parent_id }}" id="notePID">
@endif
<div class="modal fade" id="new_note" tabindex="-1" role="dialog" style="display: none;">
	<div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
			<div class="modal-body create-modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-body">
							{!! Form::open(['route' => ['notes.create']]) !!}
								{{ Form::jInput('hidden', 'ParentID', $parent_id, ['class'=>'form-control','required'=>'true']) }}
								{{ Form::jInput('hidden', 'EntityName', $entity_name, ['class'=>'form-control','required'=>'true']) }}
								<div class="form-group" >
									{{ Form::label('Description', 'Message Details') }}
									{{ Form::textarea('Description', null, ['rows'=>'3', 'class' => 'form-control withEditor', 'style' => 'min-width: 100%']) }}
									{{ Form::textarea('desc-dummy', null, ['rows'=>'3', 'class' => 'form-control desc-dummy','required'=>'true']) }}
								</div>
								<div class="form-group" >
									{{ Form::submit('Submit',['class' => 'btn btn-default btn-nolft-margin']) }}
								</div>
							{!! Form::close() !!}
							</div>
						</div>
					</div>
				</div>
			</div>
	    </div>
	</div>
</div>
@if($can_update)
<div class="modal fade" id="update_note" tabindex="-1" role="dialog" style="display: none;">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-body create-modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="form-group">
									<div class='col-md-6'>Date Created: <span id='note-date-created'></span></div>
									<div class='col-md-6'>Created By: <span id='note-createdby'></span></div>
								</div>
								<div class="form-group">
								{!! Form::open(['route' => ['notes.update']]) !!}
									{{ Form::jInput('hidden', 'NoteID', $parent_id, ['class'=>'form-control', 'id'=>'NoteID']) }}
									{{ Form::jInput('hidden', 'EntityName', $entity_name, ['class'=>'form-control']) }}
									<div class="form-group">
										{{ Form::label('Description', 'Message Details') }}
										{{ Form::textarea('Description', null, ['rows'=>'3', 'class' => 'form-control withEditor', 'style' => 'min-width: 100%']) }}
										{{ Form::textarea('desc-dummy', null, ['rows'=>'3', 'class' => 'form-control desc-dummy','required'=>'true']) }}
									</div>
									<div class="form-group">
										{{ Form::submit('Submit',['class' => 'btn btn-default btn-nolft-margin']) }}
									</div>
								{!! Form::close() !!}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endif