<script type="text/javascript" src="{{ url('plugins/jQueryUI/jquery-ui.min.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#EventDatetime").datepicker({});
	    /*$('#EventDatetime')
	        .datepicker({
	            format: 'dd/mm/yyyy',
	            startView: "decade",
	            endDate: '+0d',
	            autoclose: true
	        }
		);*/
		function getClaimTypes(id){
			$(".spinner").show();
			$.ajax({
				url:'{{ route("claim-types") }}',
				data:{'id':id},
				success: function(data){
					$("#ClaimType").html($.parseHTML(data));
					$(".spinner").hide();
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
		$('#PolicyType').on('change', function(){
			getClaimTypes($("#PolicyType").val());
		});

		$("#ClaimType").on('change', function(){
			window.location = $(this).attr("url");
		});

		/*$('.cd-breadcrumb.triangle a').click(function() {
			var id = $(this).attr('id');

			hide_show_breadcrumb(id);
			$(this).parent().addClass('current');
		});*/
		$('.btn-breadcrumb-trigger').click(function() { //next and previous button
			var id = $(this).attr('id');

			hide_show_breadcrumb(id);
			$('.cd-breadcrumb.triangle').find('a#'+id).parent().addClass('current');
		});

		$(".datepicker").datepicker({});

		function hide_show_breadcrumb(id) {
			$('.breadcrumb-content-div').removeClass('hide');
			$('.breadcrumb-content-div').addClass('hide');
			$('.breadcrumb-content-div#'+id).removeClass('hide');
			$('.cd-breadcrumb.triangle li').removeClass('current');
		}
	});
</script>