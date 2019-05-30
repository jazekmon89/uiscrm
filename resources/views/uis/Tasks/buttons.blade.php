@php
  $icons = ['','<i class="fa fa-fw fa-dollar"></i>', '<span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span>','<span class="glyphicon glyphicon-bell" aria-hidden="true"></span>','<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>','<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>','<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>','<span class="glyphicon glyphicon-time" aria-hidden="true"></span>','<span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>'];
  $dividers = ['0','1','6','7'];
@endphp
<div class="btn-group">
  <button type="button" class="btn btn-default" id="complete_task">Complete Task</button>
</div>
<div class="btn-group">
  <button type="button" id="task-createb" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" {{ empty($ParentID) || empty($EntityName)?'disabled':'' }}>
    Create Task<span class="caret"></span>
  </button>
  <ul id="tasks-menu" class="dropdown-menu">
  @php $counter = 0; @endphp
  @if(isset($display_texts))
    @foreach($display_texts as $k=>$i)
      @if($k > 0)
        <li><a href="#" data-toggle="modal" data-target="#new_task" data-type="{!! $k !!}">{!! array_key_exists($counter, $icons)?$icons[$counter]:'' !!}&nbsp;&nbsp;{{ $i }}</a></li>
        @if (in_array($counter, $dividers))
          <li role="separator" class="divider"></li>
        @endif
      @endif
    @php $counter++; @endphp
    @endforeach
  @endif
  </ul>
</div>