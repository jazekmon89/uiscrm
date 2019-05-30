<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-body">
        {!! Form::open(['route' => ['task-create'],'files'=>true]) !!}
          {{ Form::jInput('hidden', "ParentID", $parent_id, ['class'=>'form-control']) }}
          {{ Form::jInput('hidden', "EntityName", $entity_name, ['class'=>'form-control']) }}
        <div class="form-group" >
          {{ Form::label('Description', 'Assign task to:') }}
          {{ Form::jInput('select', "assigned_to", $assignees, null, ['class'=>'form-control']) }}
          {{ Form::label('Description', 'Task Request:') }}
          {{ Form::jInput('select', "task_request", $task_list, null, ['class' => 'form-control']) }}
          {{ Form::label('Description', 'Attachment:') }}
          {{ Form::file('files[]', ['multiple' => true, 'class' => 'form-control']) }}
        </div>
        <div class="form-group" >
          {{ Form::label('Description', 'Message Details') }}
          {{ Form::textarea('message_details', null, ['rows'=>'3', 'class' => 'form-control', 'style' => 'min-width: 100%']) }}
        </div>
        <div class="form-group" >
          {{ Form::submit('Assign Task',['class' => 'btn btn-default btn-nolft-margin']) }}
        </div>
        {!! Form::close() !!}
      </div>
    </div>
    </div>
  </div>  
</div>