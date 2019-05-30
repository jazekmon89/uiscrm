<script type="text/javascript" src="{{ url('plugins/daterangepicker/moment.js') }}"></script>
<script type="text/javascript" src="{{ url('js/datetimepicker/bootstrap-datetimepicker.js') }}"></script>
<script type="text/javascript" src="{{ url('plugins/jQueryUI/jquery-ui.min.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function(){
		var current_id = null, elem_id = null;
	    /*$('#EventDatetime')
	        .datepicker({
	            format: 'dd/mm/yyyy',
	            startView: "decade",
	            endDate: '+0d',
	            autoclose: true
	        }
		);*/
		function getClaimTypes(id1,id2,id3){
			$(".spinner").show();
			$.ajax({
				url:'{{ route("claim-types") }}',
				data:{'id1':id1,'id2':id2,'id3':id3},
				success: function(data){
					$("#claim_types").html($.parseHTML(data));
					$(".spinner").hide();
					$('#policies').val("{{ $claim_type }}");
					if($("#claim_types").val() != "{{ $claim_type }}");
						window.location = $("#claim_types option:selected").attr("url");
				}
			});
		}
		if($(".GST").length && $(".GST").val() == 'Y')
			$(".GST-follow-up").show();
		$(".GST").on("change", function(){
			if($(this).val() == "Y")
				$(".GST-follow-up").show();
			else
				$(".GST-follow-up").hide();
		});
		$('#EventDatetime').datetimepicker({
   			format: 'DD/MM/YYYY hh:mm:ss',
   			viewMode: 'years',
   		});
   		$('#policies').val("{{ $policy_policytype }}");
   		$('#policies').on('change', function(){
   			var policy_policytype = $(this).val().split("/");
   			getClaimTypes(policy_policytype[0], policy_policytype[1], $("#OrganisationID").val());
   		});
   		$("#claim_types").on("change", function(){
   			if($(this).val() != "{{ $claim_type }}")
   				window.location = $("#claim_types option:selected").attr("url");
   		})
	});
</script>