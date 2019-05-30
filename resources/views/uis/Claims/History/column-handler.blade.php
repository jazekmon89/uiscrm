<script>
	jQuery(document).ready(function($) {
		$(".Claims.history tbody tr").on("click", function(){
			var claim_data = null;
			try{
				claim_data = JSON.parse($(this).attr("data-claim"));
				for(var i in claim_data){
					console.log("i: "+i);
					if($("#"+i).length)
						$("#"+i).val(claim_data[i]);
				}
			}catch(err){
				console.log(err);
			}
		});
		$("#organisations").on("change", function(){
			$("#policies").html('');
			$.ajax({
				url: "{{ url('/claims/getpolicyselection')}}"+"/"+$(this).val(),
				success: function(data){
					$("#policies").html(data);
				}
			});
		});
		$("#policies").on("change", function(){
			window.location = $(this).find("option:selected").attr("data-url");
		});
	});
</script>