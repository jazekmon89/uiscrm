<script type="text/javascript">
  $(document).ready(function(){
    $.ajax({
      url: "{{ URL::route('task-form', [$TaskTypeID, $ParentID, $EntityName]) }}",
      success: function(data){
        $("#new_task .modal-body").html(data);
      }
    });
    $('#spinner-wrapper').modal({backdrop: 'static', keyboard: false});
    $.ajax({
      url: "{{ $list_url }}",
      success: function(data){
        $("#tasksTable tbody").html(data);
        $('#spinner-wrapper').modal('hide');
      }
    })
    function loadList(){
      $('#spinner-wrapper').modal({backdrop: 'static', keyboard: false});
      $.ajax({
        url: "{{ $list_url }}",
        success: function(data){
          $("#tasksTable tbody").html(data);
          $('#spinner-wrapper').modal('hide');
        }
      })
    }
    $("#new_task").on('shown.bs.modal', function(e){
      var timer = setInterval(function(){
        if($("#task_request").length){
          $("#task_request").val($(e.relatedTarget).data('type'));
          clearInterval(timer);
        }
      },200);
    });
    loadList();
  });
</script>