@if($can_update)
/*function loadTaskFiles(ParentID){
  $.ajax({
    url: "{{ url('/tasks/files/') }}/"+ParentID,
    success: function(data){
      $("#task_existing_files").html(data);
    }
  });
}*/
@endif
@if(!$isHideList)
function toggleComplete(){
  if($("#tasksTable tbody tr input:checked").length)
    $("#complete_task").attr("disabled",false);
  else
    $("#complete_task").attr("disabled",true);
}
function addTaskCellSelectEvent(){
  $("#tasksTable tbody tr td:not(.task-update-cell)").off();
  $("#tasksTable tbody tr td:not(.task-update-cell)").on("click", function(e){
    if(e.target.type != 'checkbox'){
      var first_checkbox_elem = $($(this).parents("tr").get()).find("td:first input");
      if($(first_checkbox_elem).prop('checked') == true)
        $(first_checkbox_elem).prop('checked', false);
      else
        $(first_checkbox_elem).prop('checked', true);
      toggleComplete();
    }
  });
  $("#tasksTableHeadCB").off();
  $("#tasksTableHeadCB").on("click", function(){
    var checkbox_elems = $("#tasksTable tbody tr td input");
    for(var i = 0; i < checkbox_elems.length; i++){
      $(checkbox_elems[i]).prop('checked', $("#tasksTableHeadCB").prop('checked'));
    }
    $("#tasksTableFootCB").prop('checked', $("#tasksTableHeadCB").prop('checked'));
  });
  $(".task-edit").off();
  $(".task-edit").on("click", function(){
    //$("#task_update .modal-body").html('');
    $("#task_update").modal("show");
    try{
      var data = JSON.parse($(this).attr('data-all'));
      $("#task_update .TaskID").val(data.TaskID);
      $("#task_update .assigned").val(data.AssignedID);
      $("#task_update .task-request").val(data.TaskTypeID);
      $("#task_update .subject").val(data.Subject);
      //$("#task_update .existing-attachments").html(data.ExistingAttachments);
      $("#task_update .description").val(data.Description);
      $("#task_update .due-date").val(data.DueDateTime);
      $("#task_update .task-status").val(data.StatusID);
      initFileRemoveEvent();
    }catch(err){
        // catch error!
    }
    var tID = $(this).attr("data-pid");
    //loadTaskUpdateForm(tID);
  });
  $(".task-delete").off();
  $(".task-delete").on("click", function(){
    if(confirm("{{ trans('messages.tasks_confirm_delete_msg') }}")){
      $.ajax({
        url: "{{ route('task-ajax-delete') }}",
        type: "POST",
        data: {"TaskID":$(this).data("pid"), "_token": window.Laravel['csrfToken']},
        success: function(data){
          if(data == 'false')
            alert('Failed to delete Task.');
          else if(data == 'true' && $("#taskPID").length){
            loadTaskList($("#taskPID").val());
          }
        }
      });
    }
  });
}
function updateTaskPID(pID){
  $("#ParentID").val(pID);
  if(pID != null)
    $("#task-createb").attr("disabled",false);
}
function loadTaskList(pID){
  updateTaskPID(pID);
  $('#spinner-wrapper').modal({backdrop: 'static', keyboard: false});
  $.ajax({
    url: "{{ $list_url }}"+(pID?'/'+pID:''),
    success: function(data){
      $("#tasksTable tbody").html(data);
      addTaskCellSelectEvent();
      $('#spinner-wrapper').modal('hide');
    }
  });
}
@endif
@if(!empty($update_form_url) && $can_update)
/*function loadTaskUpdateForm(tID){
  tID = tID === undefined?null:tID;
  $.ajax({
    url: "{{ route('task-update-interface') }}",
    data: {EntityName:'{{ $EntityName }}',ParentID:tID,ClientID:'{{ $ClientID }}',OrganisationID:'{{ $OrganisationID }}',TaskTypeID:'{{ $TaskTypeID }}'},
    success: function(data){
      $("#task_update .modal-body").html(data);
    }
  });
}*/
@endif
@if(!$isHideList)
function taskSelectEvent(){
  if($("#taskPID").length){
    var tPID = $("#taskPID").val();
    updateTaskPID(tPID);
    addTaskCellSelectEvent();
    @if($can_update)
    //loadTaskFiles(tPID);
    @endif
  }
}
@endif
@if(!$hideButtons && !$isHideCompleteButton)
function updateTaskCompleted(TaskIDs){
  var pid = null;
  if($("#taskPID").length)
    pid = $("#taskPID").val();
  $.ajax({
    url: "{{ route('task-update-completed') }}",
    type: "POST",
    data: {task_ids:TaskIDs, "_token": window.Laravel['csrfToken']},
    success: function(data){
      loadTaskList(pid);
      alert(data);
    }
  });
}
@endif
@if($can_update)
/*function loadExistingAttachmentsWidget(ParentID){
  $.ajax({
    url: "{{ $files_widget_url }}/"+ParentID,
    success: function(data){
      $(".existing-attachments").html(data);
      initFileRemoveEvent();
    }
  })
}
function initFileRemoveEvent(){
  $(".existing-attachments .remove").off();
  $(".existing-attachments .remove").on("click", function(){
    if(confirm("{{ trans('messages.file_confirm_delete_msg') }}")){
      $.ajax({
        url: "{{ route('attachments.delete') }}",
        type: "POST",
        data: {"FileAttachmentID":$(this).data("fid"), "_token": window.Laravel['csrfToken']},
        success: function(data){
          if(data == 'false')
            alert('Failed to remove attachment.');
          else{
            loadExistingAttachmentsWidget($("#UpdateTaskID").val());
            loadTaskList($("#taskPID").val());
          }
        }
      });
    }
  });
}*/
@endif
$(document).ready(function(){
  $(".due-date").datetimepicker();
  @if(!$isHideList)
  window.Laravel['tasklisturl'] = "{{ $list_url }}";
  //$('#spinner-wrapper').modal({backdrop: 'static', keyboard: false});
  @endif
  /*$.ajax({
    url: "{#{ route('task-create-interface') }#}",
    data: {'EntityName':'{{ $EntityName }}','ParentID':'{{ $ParentID }}','ClientID':'{{ $ClientID }}',OrganisationID:'{{ $OrganisationID }}','TaskTypeID':'{{ $TaskTypeID }}'},
    success: function(data){
      $("#new_task .modal-body").html(data);
    }
  });*/
  @if(!$hideButtons)
  $("#new_task").on('shown.bs.modal', function(e){
    var timer = setInterval(function(){
      if($("#task_request").length){
        $("#task_request").val($(e.relatedTarget).data('type'));
        clearInterval(timer);
      }
    },200);
  });
  @endif
  @if(!$hideButtons && !$isHideCompleteButton)
  $("#complete_task").on("click", function(){
    if($("#tasksTable tr input:checked").length){
      var task_ids = new Array();
      $("#tasksTable tbody tr input:checked").each(function(k,i){
        var task_id = $($(i).parents('tr')).attr("data-id");
        if(task_id.length)
          task_ids.push($($(i).parents("tr").get()).attr("data-id"));
      });
      if(task_ids.length)
        updateTaskCompleted(task_ids);
    }
  });
  @endif
  $('#new_task').on('hidden.bs.modal', function () {
    $(this).find('form')[0].reset();
  });
  @if(!$isHideList)
  //loadTaskList();
  addTaskCellSelectEvent();
  toggleComplete();
  @endif
});