function loadAttachmentsList(pID){
	$.ajax({
		url: "{{ $list_url }}/"+pID,
		success: function(data){
			$("#attachmentsTable tbody").html(data);
			initAttachmentsEditRemove();
		}
	});
}

function updateAttachmentsPID(pID){
	$("#attachmentsPID").val(pID);
}

function applyRequiredFields(elem1, elem2){
	if(!$(elem1).val().length && !$(elem2).val().length)
		$(".attachment-title, .attachment-comments").prop('required', true);
	else
		$(".attachment-title, .attachment-comments").prop('required', false);
}

function initAttachmentsEditRemove(){
	
	$(".attachment-edit").off();
	$(".attachment-edit").on("click", function(){
		$("#update_attachment").modal("show");
		var data = null;
		try{
			data = $(this).data('all');
			$("#FileAttachmentID").val(data.FileAttachmentID);
			$(".attachment-title").val(data.Title);
			$(".attachment-comments").val(data.Comments);
			$(".attachments-dtid").val(data.DocumentTypeID);
			applyRequiredFields($(".attachment-title"), ".attachment-comments");
			applyRequiredFields($(".attachment-comments"), ".attachment-title");
		}catch(err){
			// error here
		}
	});
	$(".attachment-title").on('keyup', function(){
		console.log('hehe');
		applyRequiredFields(this, ".attachment-comments");
	});
	$(".attachment-comments").on('keyup', function(){
		applyRequiredFields(this, ".attachment-title");
	});
	$(".attachment-delete").on("click", function(){
		if(confirm("{{ trans('messages.file_confirm_delete_msg') }}")){
			$.ajax({
				url: "{{ route('attachments.delete') }}",
				type: "POST",
				data: {"FileAttachmentID": $(this).data('aid'), "_token": window.Laravel['csrfToken']},
				success: function(data){
					if(data == 'false')
						alert('{{ trans('messages.file_delete_failed') }}');
					else if(data == 'true' && $("#attachmentsPID").length){
						loadAttachmentsList($("#attachmentsPID").val());
					}
				}
			});
		}
	});
}

$(document).ready(function(){
	initAttachmentsEditRemove();
});