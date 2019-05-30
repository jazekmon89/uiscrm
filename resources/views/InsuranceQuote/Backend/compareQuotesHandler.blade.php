<script>

$(function() {

	var currentQuoteRefNum = '';
	var QuoteID = '';

	$.each( $('.bem-table__container'), function( key, value ) {

	  var id = $(this).attr('id');
	  var quoteId = $(this).data('insurancequoteid');


	  if (key === 0) {
	  	$(this).css('display', 'block');
	  	$('.form-tab-step#'+id).addClass('active'); 

	  	currentQuoteRefNum = id;
	  	QuoteID = quoteId;
	  	
	  }
	});


	$('.form-tab-step').click(function() {
		var id = $(this).attr('id');
		var quoteId = $(this).data('insurancequoteid');

		currentQuoteRefNum = id;
		QuoteID = quoteId;

		$('.form-tab-step').removeClass('active'); 
		$('.form-tab-step#'+id).addClass('active'); 
		$('.bem-table__container').css('display', 'none');
		$('.bem-table__container#'+id).css('display', 'block');

	});

	$('#edit_compare_quotes').on('show.bs.modal', function() {

		$.ajax({
			url: '{{ route('insurancequotes.getCompareQuote') }}',
			type: 'post',
			data: {_token : '{{ csrf_token() }}', QuoteID: QuoteID},
			success: function(result) {
				$('#edit_compare_quotes .modal-body').html(result.data);
			},
			error: function(xhr) {
				alert("Ops! Something wen't wrong please try again later");
			}
		});


    });


    $(document).on('submit', 'form#insuranceQuote-update', function(e) {
    	e.preventDefault();

    	//var form = $(this).serializeArray();

    	var form = $(this).serialize();


    	$.ajax({
			url: '{{ route('insurancequotes.update') }}',
			type: 'post',
			data: {
				_token : '{{ csrf_token() }}', 
				UnderwriterID : $('#QuoteDetails-Quote-Underwriter').val(),
				Classification : $('#QuoteDetails-Quote-Classification').val(),
				StartDate : $('#QuoteDetails-Quote-StartDate').val(),
				EndDate : $('#QuoteDetails-Quote-EndDate').val(),
				EffectiveDate : $('#QuoteDetails-Quote-EffectiveDate').val(),
				ExpiryDate : $('#QuoteDetails-Quote-ExpiryDate').val(),
				FinalizedDate : $('#QuoteDetails-Quote-FinalizedDate').val(),
				Product : $('#QuoteDetails-Quote-Product').val(),
				Premium : $('#QuoteDetails-Quote-Premium').val(),
				Excess : $('#QuoteDetails-Quote-Excess').val(),
				ImposedExcess : $('#QuoteDetails-Quote-ImposedExcess').val(),
				PolicyType : $('#QuoteDetails-Quote-PolicyType').val(),
				currentUserId : $('#QuoteDetails-Quote-currentUserId').val(),
				InsuranceQuoteID : $('#QuoteDetails-Quote-InsuranceQuoteID').val(),
				RFQID : $('#QuoteDetails-Quote-RFQID').val(),
				AddressID : $('#QuoteDetails-Quote-AddressID').val(),
				PolicyTypeID : $('#QuoteDetails-Quote-PolicyTypeID').val(),
				currentUserId : $('#QuoteDetails-Quote-currentUserId').val()
			},
			success: function(result) {
				console.log(result); 
				console.log('hello world'); 
			},
			error: function(xhr) {
				console.log(xhr); 
				alert("Ops! Something wen't wrong please try again later");
			}
		});

    });

    $(".datetimepicker").datetimepicker({});

});

</script>