@if(!$hideAddAttachmentsButton)
<div class="form-group">
	<div class="btn-group">
	  <button type="button" class="btn btn-default" data-toggle="modal" data-target="#new_attachment" id="add_attachment">Add Attachments</button>
	</div>
</div>
@endif
@if(!$hideList)
<div class = "form-group">
	<table id="attachmentsTable" class="table table-bordered table-striped">
		<thead>
			<th>File Name</th>
			<th>Title</th>
			<th>Comments</th>
			<th>Document Type</th>
			<th>Date Created</th>
			<th>Created By</th>
			@if($can_update)
			<th></th>
			@endif
		</thead>
		<tbody>
			{!! $attachments_list !!}
		</tbody>
	</table>
</div>
<input type="hidden" value="{{ $parent_id }}" id="attachmentsPID">
@endif
<div class="modal fade" id="new_attachment" tabindex="-1" role="dialog" style="display: none;">
	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
			<div class="modal-body create-modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-body">
							{!! Form::open(['route' => ['attachments.direct-upload'],'files'=>true]) !!}
								{{ Form::jInput('hidden', 'ParentID', $parent_id, ['class'=>'form-control']) }}
								{{ Form::jInput('hidden', 'EntityName', $entity_name, ['class'=>'form-control']) }}
								<div class="form-group" >
									{{ Form::label('Description', 'Document Type:') }}
									{{ Form::jInput('select', 'DocumentTypeID', $document_types, null, ['class' => 'form-control','required'=>true]) }}
									{{ Form::label('Description', 'Attachment:') }}
									{{ Form::file('files[]', ['multiple' => true, 'class' => 'form-control','required'=>true]) }}
								</div>
								<div class="form-group" >
									{{ Form::submit('Add attachment(s)',['class' => 'btn btn-default btn-nolft-margin']) }}
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
<div class="modal fade" id="update_attachment" tabindex="-1" role="dialog" style="display: none;">
	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
			<div class="modal-body create-modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-body">
							{!! Form::open(['route' => ['attachments.update'],'files'=>true]) !!}
								{{ Form::jInput('hidden', 'FileAttachmentID', null, ['class'=>'form-control']) }}
								<div class="form-group">
									{{ Form::label('Description', 'Title:') }}
									{{ Form::jInput('text', 'Title', null, ['class'=>'form-control attachment-title']) }}
									{{ Form::label('Description', 'Comments') }}
									{{ Form::textarea('Comments', null, ['rows'=>'3', 'class' => 'form-control attachment-comments', 'style' => 'min-width: 100%']) }}
									{{ Form::label('Description', 'Document Type:') }}
									{{ Form::jInput('select', 'DocumentTypeID', $document_types, null, ['class' => 'form-control attachments-dtid','required'=>true]) }}
								</div>
								<div class="form-group">
									{{ Form::submit('Update',['class' => 'btn btn-default btn-nolft-margin']) }}
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
@endif