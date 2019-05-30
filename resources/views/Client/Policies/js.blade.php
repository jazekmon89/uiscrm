<script>
	jQuery(document).ready(function($) {
		$('body').on('click', 'tr.client-policies-list', function(e){
			// alert($(this).data('id'));
			var url = '{{ route("get.policy.details", ['POLICYID', '']) }}';
			$("#tasksTable tbody").html('');
			url = url.replace('POLICYID', $(this).data('id')); 

				/*$.ajax({
					url: window.Laravel['tasklisturl']+'/'+$(this).data('id'),
					success: function(data){
						$("#tasksTable tbody").html(data);
						taskSelectEvent();
					}
				});*/
				loadTaskList($(this).data('id'));
				loadNotesList($(this).data('id'));
				loadAttachmentsList($(this).data('id'));

				$.ajax({
					url: url,
					success: function(result) {
						//$('.client_policy_notes').html('<h3>Notes</h3>');
						$('.client_policy_tasks').html('<h3>Policy Tasks</h3>');
						//$('.client_policy_attachments').html('<h3>Attachment</h3>');

						$('span.InsuredName').text(result.details.Client.InsuredName);
						$('#ReferenceNo').val(result.details.PolicyRefNum);
						$('#PolicyNo').val(result.details.PolicyNum);
						$('#QuoteNo').val(result.details.InsuranceQuote.QuoteNum);
						$('#InsuredName').val(result.details.Client.InsuredName);
						$('#Classification').val(result.details.InsuranceQuote.Classification);
						$('#Underwriter').val(result.details.Underwriter.CompanyName);
						$('#Product').val(result.details.Product);
						$('#Premium').val(result.details.Premium);
						$('#CoverStartDate').val(result.details.CoverStartDateTime);
						$('#CoverEndDate').val(result.details.CoverEndDateTime);
						$('#Balance').val(result.details.BalanceOwed);
						$('#InvoiceNo').val('-');

						var address = '';
						if (result.details.AddressID != null) {
							//address = result.details.Address.StreetNumber+' St., ' +result.details.Address.StreetName+ ' ' +result.details.Address.City+ ' City '+result.details.Address.State;
							address = result.details.Address.AddressLine1+ ' ' +result.details.Address.City+ ' City '+result.details.Address.State;
						}
						$('#Address').val(address);

						/*var html = '';
						html += '<table class="table table-bordered table-striped">'
									  +	'<thead>'
										  +	'<tr>'
											  +	'<th>Date Created</th>'
											  +	'<th>Created by</th>'
											  +	'<th>Description</th>'
										  +	'</tr>'
									  +	'</thead>';

						$.each(result.notes, function(a,b){ 
							$.each(b, function(c,d){
								html += '<tr>'
										+ '<td>'+d.CreatedDateTime+'</td>'
										+ '<td>'+d.CreatedBy+'</td>'
										+ '<td>'+d.Description+'</td>'
									  + '</tr>';
							}); 
						});

						html += '</table>';
						$('.client_policy_notes').append(html); */


						/*$.each(result.tasks, function(a,b){ 
							$.each(b, function(c,d){
								$('.client_policy_tasks').append('<p>Date Created: '+d.CreatedDateTime+'<p>');
								$('.client_policy_tasks').append('<p>Created By: '+d.CreatedBy+'<p>');
								$('.client_policy_tasks').append('<p>Description: '+d.Description+'<p>');

							}); 

						});

						html = '';
						html += '<table class="table table-bordered table-striped">'
									  +	'<thead>'
										  +	'<tr>'
											  +	'<th>Title</th>'
											  +	'<th>Date Modified</th>'
											  +	'<th>Comments</th>'
										  +	'</tr>'
									  +	'</thead>';
						$.each(result.attachments, function(a,b){
							$.each(b, function(c,d){
								html += '<tr>'
										+ '<td>'+d.Title+'</td>';

										if (d.ModifiedDateTime != null) {
											html += '<td>'+d.ModifiedDateTime+'</td>';
										} else {
											html += '<td></td>';
										}

										if (d.Comments != null) {
										 	html += '<td>'+d.Comments+'</td>';
										} else {
											html += '<td></td>';
										}
										
									  + '</tr>';
							});
						});
						html += '</table>';
						$('.client_policy_attachments').append(html);*/

					},
					error: function(xhr) {
						alert("Ops! Something went wrong please again later.");
					}
				})
		});
	});
</script>
