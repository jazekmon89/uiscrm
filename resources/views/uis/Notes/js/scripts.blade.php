tinymce.init({
	selector:'textarea.withEditor' 
});
function loadNotesList(pID){
	$.ajax({
		url: "{{ $list_url }}/"+pID,
		success: function(data){
			$("#notesTable tbody").html(data);
			initNotesEditRemove();
		}
	});
}

function updateNotePID(pID){
	$("#notePID").val(pID);
}

function initNotesEditRemove(){
	$(".note-edit").off();
	$(".note-edit").on("click", function(){
		$("#update_note").modal("show");
		var data = null;
		try{
			data = $(this).data('all');
			$("#NoteID").val(data.NoteID);
			$("#note-date-created").html(data.CreatedDateTimeFull);
			$("#note-createdby").html(data.CreatedBy);
			tinymce.editors[1].setContent(data.Description);
		}catch(err){
			// error here
		}
	});
	$(".note-delete").on("click", function(){
		if(confirm("{{ trans('messages.note_confirm_delete_msg') }}")){
			$.ajax({
				url: "{{ route('notes.delete') }}",
				type: "POST",
				data: {"NoteID": $(this).data('nid'), "_token": window.Laravel['csrfToken']},
				success: function(data){
					if(data == 'false')
						alert('Failed to delete Note.');
					else if(data == 'true' && $("#notePID").length)
						loadNotesList($("#notePID").val());
				}
			});
		}
	});
}

$(document).ready(function(){
	initNotesEditRemove();
	if(tinymce.editors[0] !== undefined){
		tinymce.editors[0].on('keyup', function(){
			$("#new_note .desc-dummy").val(tinymce.editors[0].getContent());
			tinymce.editors[0].focus();
		});
	}
	if(tinymce.editors[1] !== undefined){
		tinymce.editors[1].on('keypress', function(){
			$("#update_note .desc-dummy").val(tinymce.editors[1].getContent());
			tinymce.editors[1].focus();
		});
	}
});