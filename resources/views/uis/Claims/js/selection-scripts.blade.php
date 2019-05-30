<script type="text/javascript" src="{!! url('plugins/jQueryUI/jquery-ui.min.js') !!}"></script>
<script type="text/javascript">
	$(document).ready(function(){
		function getClaimTypes(id1, id2, id3){
			console.log(id3);
			$(".spinner").show();
			$.ajax({
				url:'{{ route("claim-types") }}',
				data:{'id1':id1,'id2':id2,'id3':id3},
				success: function(data){
					$(".claim_type").html($.parseHTML(data));
					$(".spinner").hide();
				}
			});
		}
		$('.small-box-wrapper .small-box').height(Math.max.apply(null, $.map($('.small-box-wrapper'), function(i){ return $(i).height()})));
		$('#policies').on('change', function(e){
			var data_ids = $('#policies option:selected').attr("data-polid").split("/");
			getClaimTypes(data_ids[0], data_ids[1], $("#OrganisationID").val());
		});
		$("#claim_selection").on('shown.bs.modal', function(e){
			var data_id = $(e.relatedTarget).data("id");
			if(data_id === undefined){
				$("#claim_selection").modal('toggle');
				return;
			}
			data_ids = data_id.split("/");
			$("#claim_selection .policies").val(data_ids[0]);
			$("#claim_selection .policies option:selected").attr("data-polid",data_id);
			getClaimTypes(data_ids[0], data_ids[1], $("#OrganisationID").val());
		});
		$('#submit-claim-selection').on('click', function(e){
			e.preventDefault();
			window.location = $("#claim_type option:selected").attr("url");
		});
	});
</script>