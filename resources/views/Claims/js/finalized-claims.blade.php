<script type="text/javascript" src = "//code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src = "https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function() {
    	var url = '{{ route("get-finalized-claims") }}';
    	console.log(url);

     	$('#tbl-finalized-claims').DataTable( {
       		"bDestroy": true,
          	"ajax": url,
          	"order": [[ 1, "desc" ]]
		});

		$('body').on('click', '#tbl-finalized-claims tbody tr', function(e){
			console.log($(this).attr('id').split('ClaimID-')[1]);
		});
   
  	} );
  </script>

