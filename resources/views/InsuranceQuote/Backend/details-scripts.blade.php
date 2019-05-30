	<script>
		var iErrorHandler = function(result, container) {
			var getFieldNames = function(items, base, container) {
				container = container || [];

				for(var i in items) {
					var key = base +'['+ i +']';

					if (typeof items[i] === 'object') {

						if ($.isArray(items[i])) {
							var isString = items[i].shift();

							if (typeof isString === 'string') {
								container[key] = isString;	
								continue;
							}
							items[i].unshift(isString);
						}
						getFieldNames(items[i], key, container);
					}
					else {
						if (/(\d+)/.test(i) === false) {
							if (typeof container[key] !== 'array')
								container[key] = [];
							container[key].push(items[i]);
						}
						else container[key] = items[i];
					}
				}
				return container;
			}
			container.find('.has-error').removeClass('has-error');
			container.find(".form-messages").remove();
			for(var i in result) {
				var fields = getFieldNames(result[i], i);
				for(var i in fields) {
					var inp = $('[name="'+ i +'"]');
					var msg = $.isArray(fields[i]) ? msg = fields[i][0] : fields[i];
					var parent = null;
					if (!inp.length) {
						var val = i.match(/\[([a-z\-0-9]+)\]$/i);
						
						if (val) {
							var inp = $('#qid-'+val[1]+' .question-disp > label');
						}
					} else {
						parent = $(inp.parents('.form-group:first').get(0));
					}

					inp.parents('div:first, .question-disp:first').addClass('has-error');

					if (inp.parents('.SelectMulti').length)
						$("<span class='help-block'>"+ msg +"</span>").insertAfter(inp);
					else if (inp.parents('.Boolean').length) {
						var parent = inp.parents('.Boolean:first');
						var label = parent.find('.question-disp > label');

						$("<span class='help-block'>"+ msg +"</span>")
							.insertAfter(label)
							.css({
								position: 'relative',
    							bottom: 'auto'
							});
					}
				}
			}
			var msg = '<div class="form-messages alert alert-danger grid-75 centered v-space-2x">';
			msg += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
			msg += 'Please fill the highlighted fields correctly.</div>';
						
			container.prepend($(msg));
		}
		function requote(){
			$("#new_task").modal("show");
			$("#task_request option").each(function(){
				if($(this).text().toLowerCase().trim() == "requote"){
					$("#task_request").val($(this).val());
					return true;
				}
			});	
		}
		function uploadQuotesModal()
		{

			if (!'{{ $Quote['RFQ']['ContactID'] }}')
			{
				return alert("You must match a contact first!");
			}

			$('#upload-modal').modal('show');
		}
		function reCreateRFQ(UserID, callback)
		{
			var UserID = UserID || "";
			$('#business-credentials, .Address, .address-fields')
				.find('input[type="hidden"]')
				.attr('disabled', true);

			@if($action !== 'edit')	
				toggleForms(true);		
			@endif

			$.ajax({
				type: 'post',
				data: $('.rfq-form form').serializeArray(),
				url: '{{ route('insurancequotes.re-create', [$Quote['InsuranceQuoteID'], 'CONTACTUSERID']) }}'.replace('CONTACTUSERID', UserID),
				success: function(result) 
				{
					
					if (result.success)
					{
						location.href = '{{ route('insurancequotes.view', 'InsuranceQuoteID') }}'.replace('InsuranceQuoteID', result.InsuranceQuoteID);
					}
					else
					{
						for(var i in result)
						{
							if (!isNaN(Number(i)) && typeof result[i] === 'string') 
							{
								// @todo Custom messages
								return alert(result[i]);
							}

							var s = i;
							if (i == 'Claims') s = 'InsuranceHistory';
							var a = $('#step-'+s+' a').addClass('alert alert-danger');
								a.find('.badge').remove();
								a.append('<span class="badge">!</a>');	
						}	
						ErrorHandler(result);
					}				
					if (typeof callback === 'function') {
						callback(result);
					}
				},
				error: function(xhr)
				{
					alert("Ops, something wen't wrong please check the fields and try matching again.");
					if (typeof callback === 'function')
						callback({success: false})
				}
			});
		}
		function non_proceed()
		{
			if (confirm("Are you sure to set this RFQ as non proceed ?"))
			{
				//update status
				$.ajax({
					url: '{{ route('insurancequotes.non-proceed', $QuoteID) }}',
					type: 'post',
					data: {_token : '{{ csrf_token() }}'},
					success: function(result) {
						if (result.success)
						{
							alert('RFQ has been updated to "Non proceed".');
						}
						else
						{
							alert("Ops! We're unable to update RFQ.");
						}
					},
					error: function(xhr) {
						alert("Ops! Something wen't wrong please try again later");
					}
				})
			}

			return false;
		}
		function useContact(trigger, UserID) 
		{
			var modal = $('#match-contact');

			modal.find('.inner-overlay').remove();
			modal.find('.modal-body').append("<div class='inner-overlay'>");
			
			reCreateRFQ(UserID, function(result) {
				modal.find('.inner-overlay').remove();
				if (!result.success)
				{
					var tabs = $('.rfq-form .tabs-wrapper .form-tabs');

					tabs.find('#btn-tab').remove();
					tabs.append('<li id="btn-tab"><button id="save-new-button" class="v-space btn btn-primary">Save and Match</button><li>');
					$('#save-new-button')
					.data('UserID', UserID)
					.click(function(){
						reCreateRFQ(UserID);
					});
					modal.modal('hide');
				}
			});

			return false;
		}
		function createContact(trigger)
		{
			var me = $(trigger).attr('disabled', true),
				form = me.parents('form:first');

			$.ajax({
				type: 'post',
				url: form.attr('action'),
				data: form.serializeArray(),
				success: function(result)
				{	
					if (result.success)
					{
						useContact(null, result.UserID);
						alert("New Contact has been created.")
					}
					else 
					{
						iErrorHandler(result, $('#match-contact .modal-body'))
					}
					me.attr('disabled', false);
				},
				error: function(xhr) {
					alert("Ops!, something wen't wrong please try again later.");
					me.attr('disabled', false);
				}
			})	
			return false;
		}
		var UploadFields = {
			"" : "",
			'[Quote][QuoteNum]' 				: 'Quote #',
			'[Organization][Name]' 				: 'Organization name',
			'[Client][InsuredName]' 			: 'Insured Name',
			'[Underwriter][CompanyName]' 		: 'Under writer',
			'[Quote][CoverStartDateTime]' 		: 'Cover start date',
			'[Quote][CoverEndDateTime]' 		: 'Cover end date',
			'[Quote][EffectiveDateTime]'		: 'Effective date',
			'[Quote][ExpiryDateTime]'			: 'Expiry date',
			'[Address][AddressID]'				: 'Address',
			'[Quote][Classification]'			: 'Classification',
			'[Quote][Product]'					: 'Product',
			'[Quote][Premium]'					: 'Premium',
			'[Quote][Excess]'					: 'Excess',
			'[Quote][ImposedExcess]'			: 'Imposed excess',
			'[PolicyType][Name]'				: 'Cover type',
			'[Invoice][InvoiceNum]'				: 'Invoice #'
		},
		assignFields = function() {
				var head = $('#upload-header-fields');
				var body = $('#save-quotes form table');
				var rows = body.data('quotes') || [];
				var fields = [];
				var data = [];

				head.find('select').each(function() {
					if (this.value) {
						fields.push(this);
					}
				});

				head.addClass('hidden');
				body.parent('form').removeClass('hidden');
				var list = body.find('tbody').html("");
				for(var i in fields) {
					var f = fields[i];
					var k = $(fields[i]).parent().prev('td').text();
					
					var row = $('<tr>').append('<td>'+f.options[f.selectedIndex].innerHTML+'</td>')
					for (var j in rows) {
						var i = $('<input>').attr('name', 'Quotes['+ j +']' + f.value).val(rows[j][k]);
						row.append($('<td>').append(i));
					}
					row.appendTo(body.children('tbody'));
				}
			},
		uploadQuotes = function(trigger) {

			var me = $(trigger).attr('disabled', true);
			var form = me.parents('form:first');					
			var data = new FormData();
			var files = form.find('[name="file"]').get(0).files || {};
			var that = this;

			var assignFields = function(headings, body) {
				var head = $('#upload-header-fields').removeClass('hidden');
				var body = $('#save-quotes form table').data('quotes', body);
				body.parent('form').addClass('hidden');
				var select = $('<select>');
				for(var i in that.UploadFields) {
					select.append($("<option>").val(i).text(that.UploadFields[i]));
				}
				var list = head.find('tbody').html("");
				for(var i in headings) {
					var s = select.clone();
					var r = $('<tr>');
					var heading = headings[i].toLowerCase();

					r.append('<td>'+ headings[i] +'</td>');
					r.append($('<td>').append(s));
					r.appendTo(list);

					if (heading == 'quote #') {
						s.val('[Quote][QuoteNum]').attr('disabled', true);
					}
					else if (heading == 'invoice #') {
						s.val('[Invoice][InvoiceNum]').attr('readonly', 1);
					}
					else if (heading == 'named insured') {
						s.val('[Client][InsuredName]').attr('readonly', 1);
					}
					else if (heading == 'effective') {
						s.val('[Quote][EffectiveDateTime]').attr('readonly', 1);
					}
					else if (heading == 'expiration') {
						s.val('[Quote][ExpiryDateTime]').attr('readonly', 1);
					}
					else if (heading == 'classification') {
						s.val('[Quote][Classification]').attr('readonly', 1);
					}
					else if (heading == 'premium') {
						s.val('[Quote][Premium]').attr('readonly', 1);	
					}
					else if (heading == 'product') {
						s.val('[Quote][Product]').attr('readonly', 1);	
					}
				}
			}

			data.append('_token', '{{ csrf_token() }}');

			$.each(files, function(i, file) {
				data.append('file', file);
			});

			$.ajax({
				type: 'post',
				url: form.attr('action'),
				data: data,
				mimeTypes:"multipart/form-data",
	            contentType: false,
	            cache: false,
	            processData: false,
				success: function(result) {
					if (result.headers && result.body)
						assignFields(result.headers, result.body);
					me.attr('disabled', false);
				},
				error: function(xhr) {
					alert("Ops! Something wen't wrong please try again later.");
					me.attr('disabled', false);
				}
			})

			return false;
		};

		toggleForms = function(disabled) 
		{
			$('.rfq-form').find('input, select, button').attr('disabled', !disabled);
		}

		getState = function(shortName) 
				{
					var states = {!! json_encode(all_states()) !!};

					return states[shortName] || shortName;
				},

		$(document).ready(function() {
			var action = '{{ $action }}';
			@if($action !== 'edit')
				toggleForms(false);
			@endif

			$('#tab-ContactDetails form a.btn-default').attr('href', '{{ route('rfqs.index') }}');
			$('.rfq-form form button.btn-submit').click(function(event) {
				event.preventDefault();
				$('.rfq-form').jsteps('next');
				return false;
			});
			$('.rfq-form').jsteps('unbindTabs');
			var tabs = $('.rfq-form .tabs-wrapper .form-tabs').addClass('nav nav-tabs');

			@if($action === 'edit')
				tabs.append('<li><button id="save-new-button" class="v-space btn btn-primary">Save New Version</button><li>');

				$('#save-new-button').click(function() {
				if (confirm("Are you sure you would like to save new version of this RFQ ?"))
						return reCreateRFQ("none", function(result) {
							console.log(result);
						});
					return false;
				});
			@endif
			
			$('.rfq-form .form-tab-step').click(function(){
				$('.rfq-form').jsteps('step', $(this).data('jindex') || 0);
			});
			$('.rfq-form .Address').each(function(){
				var me = $(this),
					trigger = me.find('.u-m-a'),
					hidden = me.find('input[type="hidden"]'),
					addr_type = trigger.data('address-use');

					Address = $('#'+addr_type.replace('_', '-'));

					if ($('#ContactDetails-'+ addr_type +'-AddressID').val() == hidden.val())
					{
						me.addClass('use-addr')
						  .find('input:not(.u-m-a), select').each(function(){
						  	var that = $(this);
						  	if (that.attr('type') !== 'hidden')
						  	{
						  		that.attr('disabled', true);
						  	}
						});

						trigger.each(function() {
							if (this.value)
							{
								$(this).attr('checked', true);
							}
						});
					}	

			});
			$('#match-contact #search-contact form').submit(function(){
				var btns = $('#search-fields .trigger').attr('disabled', true);
				var address = function(Address) {
					return !Address ? "" :
					[
						//Address.UnitNumber,
						//Address.StreetNumber,
						//Address.StreetName,
						Address.AddressLine1,
						Address.Country,
						getState(Address.State),
						Address.Postcode
					].join(" ").replace(/\s\s+/g, '');
				}
				$.ajax({
					url: '{{ route('search', ['FindContactByPersonalDetails']) }}',
					data: $(this).serializeArray(),
					success: function(items) {
						var tbody = $('#match-contact table tbody').html("");
						for(var i=0; i<items.length;i++)
						{
							var row = "<tr>";
							row += "<td>"+ items[i].ContactRefNum +"</td>";
							row += "<td>"+ items[i].FirstName +"</td>";
							row += "<td>"+ items[i].PreferredName +"</td>";
							row += "<td>"+ items[i].Surname +"</td>";
							row += "<td>"+ address(items[i].PostalAddress || {}) +"</td>";
							row += "<td>"+ items[i].EmailAddress +"</td>";
							row += "<td><a href='#' onclick='useContact(this, \""+items[i].UserID+"\")'>Use</a></td>";
							row += "</tr>";

							$(row).appendTo(tbody);
						}
						btns.attr('disabled', false);
					},
					error: function(xhr) {
						btns.attr('disabled', false);
					}
				});
				return false;
			});

			$('.datetimepicker').each(function() {
				var me = $(this),
					format = me.data('date-format') || 'DD/MM/YYYY H:i:s';

				me.datetimepicker({
					format: format,
					viewMode: 'years'
				})
			});
			$('#upload-quotes input[name="file"]').change(function(){
				$(this).parent().siblings('input').val(this.value);
			});
			$('#search-fields .address .dropdown-menu').on('click', function(e) {
				e.stopPropagation();
			});
			$('input[name="use_home_addr"]').change(function() {
				var me = $(this);
				if (me.val() == 'Y') {
					$('#nc-mail-address').find('input, select').attr('disabled', true);
				}
				else {
					$('#nc-mail-address').find('input, select').attr('disabled', false);
				}
			});
			var afields = $('#search-fields .address .dropdown-menu')
				.find('input, select')
				.change(generateAddress);
			function generateAddress()
			{
				var address = "";
				afields.each(function() {
					var val = $(this).val().trim();
					
					if (val) address += " "+ val;
				});
				$('#AddressFull').val(address);
			}
		});
	</script>