<script>
	jQuery(document).ready(function($) {
		var getData = function(depth, raw, def) {
				var depth = typeof depth === 'string' ? depth.split('.') : depth;
				var ref = raw;
				for(var i in depth) {
					if (ref[depth[i]]) {
						ref = ref[depth[i]];
					}
					else return def;
				}
				return ref;
			},
			clearForms = function() {
				//$('#in-q-cid,#in-q-insuredname,#in-q-companyname,#in-q-aid,#in-q-unitnumber,#in-q-streetnumber,#in-q-streetname,#in-q-city,#in-q-state,#in-q-country,#in-q-postcode,#in-q-ctid,#in-q-firstname,#in-q-lastname,#city-q-email,#in-q-companyname,#in-q-mobilenum,#no-of-pols,#no-of-recommends').val("");
				$('#in-q-cid,#in-q-insuredname,#in-q-companyname,#in-q-aid,#in-q-addressline1,#in-q-addressline2,#in-q-city,#in-q-state,#in-q-country,#in-q-postcode,#in-q-ctid,#in-q-firstname,#in-q-lastname,#city-q-email,#in-q-companyname,#in-q-mobilenum,#no-of-pols,#no-of-recommends').val("");
				$('#tasks-list tbody tr:not(.empty)').remove();
			},
			updateContact = function() {
				var btn = $(this);
				var form = $(btn.data('form') || "#contact-detail-forms .default");
				var Contact = btn.data('contact')
				var data = {
					FirstName: form.find('.in-q-firstname').val(),
					PreferredName: form.find('.in-q-preferredname').val(),
					Surname: form.find('.in-q-lastname').val(),
					EmailAddress: form.find('.in-q-email').val(),
					MobilePhoneNumber: form.find('.in-q-mobilenum').val()
				};
				var url = '{{ route("client.contact-update", ['CLIENTID', 'CONTACTID']) }}';

				btn.attr('disabled', true);
				url = url.replace('CLIENTID', Contact.ClientID)
				   .replace('CONTACTID', Contact.ContactID);
				 console.log(btn.data('form'));
				$.ajax({
					url: url,
					data: data,
					success: function(result) {
						btn.attr('disabled', false);
						
						if (result.success)
							alert("Contact Details has been updated.");
						else
							alert("Unable to udpate contact details.");	
						
					},
					error: function(xhr) {
						alert("Ops! Something went wrong please again later.");
					}
				})

			},
			contactNavigations = {
				buttons: $('#contact-detail-forms .navigation button'),
				forms: $('#contact-detail-forms .form'),
				currentForm: 0,
				next: function() {
					if (!this.forms || !this.forms.length || this.currentForm >= this.forms.length) {
						return;
					}
					this.forms.addClass('hidden');
					var form = this.forms.get(this.currentForm + 1);
					form.removeClass('hidden');
				},
				prev: function() {
					if (!this.forms || !this.forms.length || this.currentForm == 0) {
						return;
					}
					this.forms.addClass('hidden');
					var form = forms.get(this.currentForm + 1);
					form.removeClass('hidden');
				},
				init: function() {
					var that = this;
					
					if (!this.forms.length || this.forms.length <= 1) 
						return this.buttons.attr('disabled', true);

					this.buttons.click(function() {
						var me = $(this);
						var trigger = me.data('trigger');

						if (typeof that[trigger] === 'function') {
							return that[trigger].apply(that, this);
						}
					});
				}
			},
			search = function() {
				var form = $('.client-profiles form'),
					data = form.data('search') || {},
					toggleButtons = function(toggle) {
						$('#search-fields input').attr('readonly', toggle);
						$('#search-fields button').attr('disabled', toggle);
					},
					makeRow = function(data) {
						var row = "<tr>";
						/*row += "<td>"+ getData('InsuredName', data, 'N/A') +"</td>";
						row += "<td>"+ getData('InsurableBusiness.CompanyName', data, 'N/A') +"</td>";
						row += "<td>"+ getData('Contact.MobilePhoneNumber', data, 'N/A') +"</td>";
						row += "<td>"+ getData('Contact.EmailAddress', data, 'N/A') +"</td>";
						row += "<td>"+ getData('ModifiedDateTime', data, 'N/A') +"</td>";
						row += "<td>"+ getData('InsurancePolicy.PolicyNum', data, "N/A") +"</td>";*/
						row += "<td>"+ getData('ClientRefNum', data, '') + "</td>";
						row += "<td>"+ getData('InsuredName', data, '') + "</td>";
						row += "<td>"+ getData('Contact.FirstName', data, '') + "</td>";
						row += "<td>"+ getData('Contact.PreferredName', data, '') + "</td>";
						row += "<td>"+ getData('Contact.Surname', data, '') + "</td>";
						//row += "<td>"+ (getData('HomeAddress.UnitNumber', data, '')+' '+getData('HomeAddress.StreetNumber', data, '')+' '+getData('HomeAddress.StreetName', data, '')+' '+getData('HomeAddress.City', data, '')+' '+getData('HomeAddress.State', data, '')+' '+getData('HomeAddress.Postcode', data, '')+' '+getData('HomeAddress.Country', data, '')).replace(/ +(?= )/g,'')+"</td>";
						row += "<td>"+ (getData('HomeAddress.AddressLine1', data, '')+' '+getData('HomeAddress.City', data, '')+' '+getData('HomeAddress.State', data, '')+' '+getData('HomeAddress.Postcode', data, '')+' '+getData('HomeAddress.Country', data, '')).replace(/ +(?= )/g,'')+"</td>";
						row += "<td>"+ getData('Contact.MobilePhoneNumber', data, '') + "</td>";
						row += "<td>"+ getData('Contact.EmailAddress', data, '') + "</td>";
						row += "<td>"+ getData('InsurableBusiness.AustralianBusinessNumber', data, '') + "</td>";
						row += "<td>"+ getData('InsurableBusiness.TradingName', data, '') + "</td>";
						row += "<td>"+ getData('InsurableBusiness.BusinessStructureType', data, '') + "</td>";
						//row += "<td>"+ getData('CreatedBy', data, '') + "</td>";
						//row += "<td>"+ getData('CreatedDateTime', data, '') + "</td>";
						//row += "<td>"+ getData('ModifiedBy', data, '') + "</td>";
						//row += "<td>"+ getData('ModifiedDateTime', data, '') + "</td>";
						row += "<td>"+ getData('Contact.MiddleNames', data, '') + "</td>";
						row += "<td>"+ getData('Contact.BirthDate', data, '') + "</td>";
						row += "<td>"+ getData('Contact.BirthDate', data, '') + "</td>";
						row += "<td>"+ getData('Contact.BirthCountry', data, '') + "</td>";
						row += "<td>"+ getData('Contact.ContactRefNum', data, '') + "</td>";
						//row += "<td>"+ getData('Contact.CreatedBy', data, '') + "</td>";
						//row += "<td>"+ getData('Contact.CreatedDateTime', data, '') + "</td>";
						//row += "<td>"+ getData('Contact.ModifiedBy', data, '') + "</td>";
						//row += "<td>"+ getData('Contact.ModifiedDateTime', data, '') + "</td>";
						//row += "<td>"+ (getData('PostalAddress.UnitNumber', data, '')+' '+getData('PostalAddress.StreetNumber', data, '')+' '+getData('PostalAddress.StreetName', data, '')+' '+getData('PostalAddress.City', data, '')+' '+getData('PostalAddress.State', data, '')+' '+getData('PostalAddress.Postcode', data, '')+' '+getData('PostalAddress.Country', data, '')).replace(/ +(?= )/g,'')+"</td>";
						row += "<td>"+ (getData('PostalAddress.AddressLine1', data, '')+' '+getData('PostalAddress.City', data, '')+' '+getData('PostalAddress.State', data, '')+' '+getData('PostalAddress.Postcode', data, '')+' '+getData('PostalAddress.Country', data, '')).replace(/ +(?= )/g,'')+"</td>";
						row += "<td>"+ getData('InsurableBusiness.IsRegisteredForGST', data, '') + "</td>";
						//row += "<td>"+ getData('InsurableBusiness.CreatedBy', data, '') + "</td>";
						//row += "<td>"+ getData('InsurableBusiness.CreatedDateTime', data, '') + "</td>";
						//row += "<td>"+ getData('InsurableBusiness.ModifiedBy', data, '') + "</td>";
						//row += "<td>"+ getData('InsurableBusiness.ModifiedDateTime', data, '') + "</td>";
						row += "</tr>";
						row = $(row)
							.data('client', data)
							//.insertBefore('.profiles form table tbody tr.empty');
							.appendTo('.profiles-list tbody');
							//console.log(data);
						bindClick(row);
					},
					toggleTableRows = function(trigger, toggle) {
						var trigger = $(trigger);
						var toggle = toggle;
						var opacity = toggle ? 0.7 : 1;
						$(trigger)
							.attr('disabled', toggle).css("opacity", opacity)
							.siblings().attr('disabled', toggle).css("opacity", opacity);
					},
					fetchClientData = function(CLIENTID, callback) {
						var url = '{{ route('client.data', ['CLIENTID', 'recommendations,current_policies,tasks']) }}';
						
						$.ajax({
							url: url.replace('CLIENTID', CLIENTID),
							success: function(client) {
								callback(client);
							},
							error: function(xhr) {
								alert("Ops! Something went wrong please again later.");
							}
						})
					},
					makeContactForms = function(ClientID, Contacts) {
						if (!Contacts.length) return;

						var formContainer = $('#contact-detail-forms');
						formContainer.children('.form:not(.default').remove();

						var baseForm = $('#contact-detail-forms .default');
						var forms = [];
						for(var i=0;i < Contacts.length;i++) {
							var form = !forms.length ? baseForm : baseForm.clone();
							
							form.attr('id', 'contact-form-'+ Contacts[i].ContactID);

							form.find('.in-q-firstname').val(Contacts[i].FirstName);
							form.find('.in-q-preferredname').val(Contacts[i].PreferredName);
							form.find('.in-q-lastname').val(Contacts[i].Surname);
							form.find('.in-q-email').val(Contacts[i].EmailAddress);
							form.find('.in-q-mobilenum').val(Contacts[i].MobilePhoneNumber);
							
							Contacts[i].ClientID = ClientID;

							form.find('.btn')
								.data('form', form.attr('id'))
								.data('contact', Contacts[i])
								.attr('disabled', false)
								.click(updateContact);

							if (!forms.length) 
								form.insertAfter(baseForm);
						}
						contactNavigations.forms = $('#contact-detail-forms .form');
						contactNavigations.currentForm = 0;
					},
					supplyContactFormData = function(ClientID, Contact) {
						$('#in-q-ctid').val(ClientID);
						$('#in-q-firstname').val(getData('FirstName', Contact, ''))
						$('#in-q-preferredname').val(getData('PreferredName', Contact, ''));		
						$('#in-q-lastname').val(getData('Surname', Contact));
						$('#in-q-email').val(getData('EmailAddress', Contact));
						$('#in-q-mobilenum').val(getData('MobilePhoneNumber', Contact, ''));

						var baseForm = $('#contact-detail-forms .form.default');
						Contact.ClientID = ClientID;
						baseForm.find('.btn')
							// .data('form', baseForm.attr('id'))
							.data('contact', Contact)
							.attr('disabled', false)
							.click(updateContact);

					},
					supplyClientTasksData = function(ClientID, Tasks) {
						var table = $('#tasks-list');
						var base = table.find('tr.empty');

						table.find('tbody tr:not(.empty)').remove();

						for(var i=0;i<Tasks.length;i++) {
							var row = '<tr>';
							var Role = Tasks[i].Role || {};
							var Status = Tasks[i].Status;
							row += '<td>'+ Tasks[i].Subject +'</td>';
							row += '<td>'+ (Role.Name || "N/A") +"</td>";
							row += '<td>'+ (Status.Name || "Open") +"</td>"
							row += '</tr>';
							$(row).insertBefore(base);
						}
						
					},
					bindClick = function(row) {
						row.click(function() {
							var me = $(this);

							if (me.is('disabled')) return;

							var	data = me.data('client');


							if (!data.ClientID) {
								return alert("Sorry, this account is not yet a Client.");
							}

							return location.href = "{{ route('client.profiles', ['CLIENTID']) }}".replace("CLIENTID", data.ClientID);

							function callback(client) {	
								$('#in-q-cid').val(data.ClientID);
								$('#in-q-insuredname').val(getData('InsuredName', data, 'N/A'))
								$('#in-q-companyname').val(getData('InsurableBusiness.CompanyName', data, 'N/A'));		
								$('#in-q-aid').val(getData('InsurableBusiness.PostalAddress.AddressID', data));
								/*$('#in-q-unitnumber').val(getData('InsurableBusiness.PostalAddress.UnitNumber', data));
								$('#in-q-streetnumber').val(getData('InsurableBusiness.PostalAddress.StreetNumber', data, 'N/A'));
								$('#in-q-streetname').val(getData('InsurableBusiness.PostalAddress.StreetName', data, 'N/A'));*/
								$('#in-q-addressline1').val(getData('InsurableBusiness.PostalAddress.AddressLine1', data));
								$('#in-q-addressline2').val(getData('InsurableBusiness.PostalAddress.AddressLine2', data));
								$('#in-q-city').val(getData('InsurableBusiness.PostalAddress.City', data, 'N/A'));
								$('#in-q-state').val(getData('InsurableBusiness.PostalAddress.State', data, 'N/A'));
								$('#in-q-country').val(getData('InsurableBusiness.PostalAddress.Country', data, 'N/A'));
								$('#in-q-postcode').val(getData('InsurableBusiness.PostalAddress.Postcode', data, 'N/A'));

								row.addClass('success');
								row.siblings().removeClass('success');

								$('.links .btn.active, .rcmds-link').each(function(){
									var me = $(this);

									me.attr('CLIENTID', data.ClientID);
									me.attr('disabled', false);
								});

								var current_policies = client.current_policies || [];
								var recommendations = client.recommendations || [];

								// makeContactForms(data, client.contacts || []);
								supplyContactFormData(data.ClientID, data.Contact || {});
								supplyClientTasksData(data.ClientID, client.tasks || []);

								$('#update-address').attr('disabled', false);
								$('#no-of-pols').val(current_policies.length);
								$('#no-of-recommends').val(recommendations.length);

								toggleTableRows(me, false);
							}
							toggleTableRows(this, true);
							return fetchClientData(data.ClientID, callback);
						});
					},
					clearRows = function() {
						//$('.profiles form table tbody tr:not(.empty)').remove();
						$('.profiles-list tbody tr:not(.empty)').remove();
						
					}

				if (data.isSearching) {
					return;
				}

				toggleButtons(data.isSearching = true);

				clearForms();

				form.data('search', data);

				$.ajax({
					url: form.attr('action'),
					data: form.serializeArray(),
					success: function(result) {
						toggleButtons(data.isSearching = false)
						form.data('search', data);
						
						
						if (!result) return alert("No results found.");

						clearRows();
						if (result.length)	
							for(var i in result) makeRow(result[i]);
					},
					error: function(xhr) {
						toggleButtons(data.isSearching = false)
						form.data('search', data);
					}
				});

				return false;
			};
		$("[name='ModifiedDate']").datepicker({
			format: 'dd-mm-yyyy',
            startView: "decade",
            endDate: '+0d',
            autoclose: true
		});
		$('#search-fields .trigger').click(search);	
		$('.links .btn, .rcmds-link').click(function(){
			top.location.href = $(this).attr('href').replace('CLIENTID', $(this).attr('CLIENTID'));
		});
		$('#update-address').click(function(){
			var button = $(this).attr('disabled', true),
				data = {
				//UnitNumber: $('#in-q-unitnumber').val(),
				//StreetNumber: $('#in-q-streetnumber').val(),
				//StreetName: $('#in-q-streetname').val(),
				AddressLine1: $('#in-q-addressline1').val(),
				AddressLine2: $('#in-q-addressline2').val(),
				City: $('#in-q-city').val(),
				State: $('#in-q-state').val(),
				Country: $('#in-q-country').val(),
				Postcode: $('#in-q-postcode').val(),
			},
			// data = $('#address-form').serializeArray(),
			url = '{{ route('client.address-update', ['CLIENTID', 'ADDRESSID']) }}';

			url = url.replace('CLIENTID', $('#in-q-cid').val())
					 .replace('ADDRESSID', $('#in-q-aid').val());	 
			$.ajax({
				url: url,
				data: data,
				success: function(result) {
					button.attr('disabled', false);
					alert("Client Address has been updated!");
				},
				error: function(xhr) {
					button.attr('disabled', false);
					alert("Ops! Something wen't wrong please try again later.")
				}

			})

			return false;
		});
		$('#contact-detail-forms .form button').click(updateContact);
		contactNavigations.init();
		/*if($(".profiles-list tbody tr").length){
			$(".profiles-list tbody tr").on("click", function(){
				window.location="{{ url('client/profiles') }}/"+$(this).attr("cid");
			});
		}
		if($(".contacts-list tbody tr").length){
			$(".contacts-list tbody tr").on("click", function(){
				window.location="{{ url('client/profiles') }}/"+$(this).attr("cid");
			});
		}*/
		$("#reset-profiles").on("click", function(){
			$("#search-fields input").each(function(){
				$(this).val('');
			});
			$(".profiles-list tbody").html('');
			$.ajax({
				url: "{{ route('client.getallprofiles') }}",
				success: function(result){
					$(".profiles-list tbody").html(result);
				}
			})
		});
	});
</script>
