@php
  $icons = ['','<i class="fa fa-fw fa-dollar"></i>', '<span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span>','<span class="glyphicon glyphicon-bell" aria-hidden="true"></span>','<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>','<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>','<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>','<span class="glyphicon glyphicon-time" aria-hidden="true"></span>','<span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>','',''];
  $dividers = ['0','1','6','7'];
@endphp
<style>
  table thead tr th{
    background: #006697;
    color: #fff;
    border: 1px inset #4b8cad;
  }
  table tr {
    border: 0 !important;
  }   
  table tr td {
    border: 1px solid #dfdbdc !important;
  }
</style>
@if($isHideList)
<div class="btn-group">
  @if(!$isHideStatusSelection && !$hideButtons)
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Status<span class="caret"></span>
  </button>
    @if(isset($task_status_list))
    <ul id="tasks-status" class="dropdown-menu">
      @foreach($task_status_list as $k=>$i)
        <li><a href="#" type="{!! $i->TaskStatusID !!}">{{ $i->DisplayText }}</a></li>
      @endforeach
    </ul>
    @endif
  @endif
</div>
@if(!$isHideCompleteButton && !$hideButtons)
<div class="btn-group">
  <button type="button" class="btn btn-default" id="complete_task">Complete Task</button>
</div>
@endif
@if(!$hideButtons)
<div class = "form-group">
  <div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Create Task<span class="caret"></span>
  </button>
  @if(isset($task_type_list))
  <ul id="tasks-menu" class="dropdown-menu">
  @php $counter = 0; @endphp
    @foreach($task_type_list as $k=>$i)
      @if($k > 0)
        <li><a href="#" data-toggle="modal" data-target="#new_task" data-type="{!! $k !!}">{!! array_key_exists($counter, $icons)?$icons[$counter]:'' !!}&nbsp;&nbsp;{{ $i }}</a></li>
        @if (in_array($counter, $dividers))
          <li role="separator" class="divider"></li>
        @endif
      @endif
    @php $counter++; @endphp
    @endforeach
  </ul>
  @endif
  </div>
</div>
@endif
@elseif($hideButtons)
<div class="bem-container__center">
  <div class="container-fluid">
    <div class="row">
      <div class="bem-table__container table-responsive">
        <div class="form-group">
          <table id="tasksTable" class="table table-hover table-striped table-fixed-header">
            <thead>
            <tr>
              <th width="5%">
                <input type="checkbox" id="tasksTableHeadCB">
              </th>
              <th>Task Type</th>
              <th>Subject</th>
              <th>Description</th>
              <th>Assigned To</th>
              <th>Due Date</th>
              <th>Status</th>
              <th>Created Date</th>
              @if($can_update)
              <th></th>
              @endif
            </tr>
            </thead>
            <tbody>
              @if(!empty($task_list))
                {!! $task_list !!}
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>    
  </div>
</div>   
@else
<div class="row">
  <!--div class = "form-group">
  @include('flash::message')
  </div-->
  <div class="col-xs-12">
    <div class="box">                    
      <!-- /.box-header -->
      <div class="box-body">
        <div class = "form-group">
        <div class="btn-group">
          @if(!$isHideStatusSelection)
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Status<span class="caret"></span>
          </button>
          @endif
          @if(!$isHideCompleteButton)
          <button type="button" class="btn btn-default" id="complete_task">Complete Task</button>
          @endif
          @if(isset($display_texts))
          <ul id="tasks-status" class="dropdown-menu">
            @foreach($task_types as $k=>$i)
              <li><a href="#" type="{!! $i->TaskStatusID !!}">{{ $i->DisplayText }}</a></li>
            @endforeach
          </ul>
          @endif
        </div>
        <div class="btn-group">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Create Task<span class="caret"></span>
          </button>
          @if(isset($task_type_list))
          <ul id="tasks-menu" class="dropdown-menu">
            @php $counter = 0; @endphp
            @foreach($task_type_list as $k=>$i)
              @if($k > 0)
                <li><a href="#" data-toggle="modal" data-target="#new_task" data-type="{!! $k !!}">{!! array_key_exists($counter, $icons)?$icons[$counter]:'' !!}&nbsp;&nbsp;{{ $i }}</a></li>
                @if (in_array($counter, $dividers))
                  <li role="separator" class="divider"></li>
                @endif
              @endif
            @php $counter++; @endphp
            @endforeach
          </ul>
          @endif
        </div>
        </div>
        <div class="bem-container__center">
          <div class="container-fluid">
            <div class="row">
              <div class="bem-table__container table-responsive">
                <div class="form-group">
                  <table id="tasksTable" class="table table-hover table-striped table-fixed-header tasks-table">
                    <thead>
                    <tr>
                      <th width="5%">
                        <input type="checkbox" id="tasksTableHeadCB">
                      </th>
                      <th>Task Type</th>
                      <th>Subject</th>
                      <th>Description</th>
                      <th>Assigned To</th>
                      <th>Due Date</th>
                      <th>Status</th>
                      <th>Created Date</th>
                      @if($can_update)
                      <th></th>
                      @endif
                    </tr>
                    </thead>
                    <tbody>
                      @if(!empty($task_list))
                        {!! $task_list !!}
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>
            </div>    
          </div>
        </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
@endif
<div class="modal fade" id="new_task" tabindex="-1" role="dialog" style="display: none;">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body create-modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-body">
                {!! Form::open(['route' => ['task-create'],'files'=>true]) !!}
                  {{ Form::jInput('hidden', 'ParentID', $parent_id, ['class'=>'form-control']) }}
                  {{ Form::jInput('hidden', 'EntityName', $entity_name, ['class'=>'form-control']) }}
                  {{ Form::jInput('hidden', 'OrganisationID', $entity_name, ['class'=>'form-control']) }}
                <div class="form-group" >
                  {{ Form::label('Description', 'Assign task to:') }}
                  {{ Form::jInput('select', 'assigned_to', $assignees, null, ['class'=>'form-control']) }}
                  {{ Form::label('Description', 'Task Request:') }}
                  {{ Form::jInput('select', 'task_request', $task_type_list, null, ['class' => 'form-control','id'=>'task_request']) }}
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
      </div>
    </div>
  </div>
</div>
@if($can_update)
<div class="modal fade" id="task_update" tabindex="-1" role="dialog" style="display: none;">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body create-modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-body">
                {!! Form::open(['route' => ['task-update'],'files'=>true]) !!}
                  {{ Form::jInput('hidden', 'TaskID', $parent_id, ['class'=>'form-control TaskID', 'id'=>'UpdateTaskID']) }}
                  {{ Form::jInput('hidden', 'EntityName', $entity_name, ['class'=>'form-control EntityName']) }}
                <div class="form-group" >
                  {{ Form::label('Description', 'Assign task to:') }}
                  {{ Form::jInput('select', 'assigned_to', $assignees, $assignee, ['class'=>'form-control assigned']) }}
                  {{ Form::label('Description', 'Task Request:') }}
                  {{ Form::jInput('select', 'task_request', $task_type_list, $task_type, ['class' => 'form-control task-request','id'=>'task-request']) }}
                  {{ Form::label('Description', 'Subject:') }}
                  {{ Form::jInput('text', 'subject', $subject, ['class' => 'form-control subject']) }}
                  {{ Form::label('Description', 'Attachment:') }}
                  <div class="existing-attachments">
                  </div>
                  {{ Form::file('files[]', ['multiple' => true, 'class' => 'form-control']) }}
                </div>
                <div class="form-group" >
                  {{ Form::label('Description', 'Message Details') }}
                  {{ Form::textarea('description', $description, ['rows'=>'3', 'class' => 'form-control description', 'style' => 'min-width: 100%']) }}
                </div>
                <div class="form-group">
                  <div class="input-group date">
                    {{ Form::label('Description', 'Due date') }}
                    <div class="input-group input-append date" id="due_date_cont">
                      {{ Form::jInput("datetime", "due_date", $due_date, ['id' => 'due_date', 'class' => 'date form-control due-date']) }}
                      <span class="input-group-addon add-on"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  {{ Form::label('Description', 'Status') }}
                  {{ Form::jInput('select', 'task_status', $task_status_list, $task_status, ['class' => 'form-control task-status','id'=>'task-status']) }}
                </div>
                <div class="form-group" >
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
</div>
@endif
<div class="modal fade" id="spinner-wrapper" tabindex="-1" role="dialog" style="display: none;">
  <div class="spinner2">
  </div>
</div>