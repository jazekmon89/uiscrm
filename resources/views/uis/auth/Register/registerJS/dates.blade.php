<script type="text/javascript">
	$(document).ready(function(){
		//$(".date_of_birth_cont .input-group.date").datepicker({});
	    $('#date_of_birth_cont')
	        .datepicker({
	            format: 'dd/mm/yyyy',
	            startView: "decade",
	            endDate: '+0d',
	            autoclose: true
	        }
		);
	});
</script>