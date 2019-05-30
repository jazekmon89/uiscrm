@php
  $icons = ['','<i class="fa fa-fw fa-dollar"></i>', '<span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span>','<span class="glyphicon glyphicon-bell" aria-hidden="true"></span>','<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>','<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>','<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>','<span class="glyphicon glyphicon-time" aria-hidden="true"></span>','<span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>'];
  $dividers = ['0','1','6','7'];
@endphp
<div class="row">
  <div class="col-xs-12">
    <div class="box">                    
      <!-- /.box-header -->
      <div class="box-body">
        <div class="btn-group">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Status<span class="caret"></span>
          </button>
          <ul id="tasks-status" class="dropdown-menu">
          @if(isset($display_texts))
            @foreach($task_types as $k=>$i)
              <li><a href="#" type="{!! $i->TaskStatusID !!}">{{ $i->DisplayText }}</a></li>
            @endforeach
          @endif
          </ul>
        </div>
        <div class="btn-group">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Create Task<span class="caret"></span>
          </button>
          <ul id="tasks-menu" class="dropdown-menu">
          @php $counter = 0; @endphp
          @if(isset($display_texts))
            @foreach($display_texts as $k=>$i)
              <li><a href="#" data-toggle="modal" data-target="#new_task" data-type="{!! $k !!}">{!! array_key_exists($counter, $icons)?$icons[$counter]:'' !!}&nbsp;&nbsp;{{ $i }}</a></li>
              @if (in_array($counter, $dividers))
                <li role="separator" class="divider"></li>
              @endif
            @php $counter++; @endphp
            @endforeach
          @endif
          </ul>
        </div>
        <table id="tasksTable" class="table table-bordered table-striped">
          <thead>
          <tr>
            <th width="5%">
              <input type="checkbox">
            </th>
            <th>Task</th>
            <th>Contact</th>
            <th>Status</th>
          </tr>
          </thead>
          <tbody>

          </tbody>
          <tfoot>
          <tr>
            <th width="5%">
              <input type="checkbox">
            </th>
            <th>Task</th>
            <th>Contact</th>
            <th>Status</th>
          </tr>
          </tfoot>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
<div class="modal fade" id="new_task" tabindex="-1" role="dialog" style="display: none;">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body create-modal-body">
        <div class="spinner">          
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="spinner-wrapper" tabindex="-1" role="dialog" style="display: none;">
  <div class="spinner2">
  </div>
</div>