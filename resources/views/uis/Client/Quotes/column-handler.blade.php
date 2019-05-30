<script>
	jQuery(document).ready(function($) {
		var address = function(Address) {
			if (!Address) return "";
			return [//Address.UnitNumber,
					//Address.StreetName,
					Address.AddressLine1,
					Address.State,
					Address.City,
					Address.Coutry,
					Address.Postcode].join(' ');
			},
			getData = function(depth, raw, def) {
				if (typeof depth === 'string')
					depth = depth.split('.');
				var ref = raw;
				for(var i in depth) {
					if (ref[depth[i]]) {
						ref = ref[depth[i]];
					}
					else return def;
				}
				return ref;
			};

		$('table.Quotes tbody').on('click', 'tr:not(.empty)', function(e) {

			if (e.target.tagName.toLowerCase() == 'input')
				return;

			var me 		= $(this),
				quote 	= me.data('quote');
				notes 	= function(Notes) {
					if (!Notes) return;

					var table = $('table#Notes tbody'),
						username = function(user) {
							return user.Username || user.EmailAddress || "";
						};

					table.find('tr:not(.empty)').remove();

					for (var i in Notes) {
						var tr = "<tr><td>"+ Notes[i].Note.CreatedDATETime +"</td>";
							tr += "<td>"+ username(Notes[i].User) +"</td>";
							tr += "<td>"+ Notes[i].Note.Description +"</td></tr>";

						table.prepend($(tr));
					}
				},
				toggleActive = function(row) {
					if (row.prop('tagName') !== 'TR')
						row = row.parents('tr:first');

					row.siblings('.success').removeClass('success');
					row.addClass('success');
				};
				
			toggleActive(me);
				
			$('#in-q-referencenum').val(quote.QuoteRefNum);
			// $('#in-q-policynum').val(policy.PolicyNum || "");
			$('#in-q-quotenum').val(quote.QuoteNum);
			$('#in-q-insuredname').val(getData('Client.InsuredName', quote));
			$('#in-q-address').val(address(quote.Address));
			
			$('#in-q-classification').val(quote.Classification);
			$('#in-q-underwriter').val(getData('Underwriter.CompanyName', quote));
			$('#in-q-product').val(quote.Product || "");
			$('#in-q-premium').val(quote.Premium || "");
			$('#in-q-excess').val(quote.Excess);

			$('#in-q-coverstartdate').val(moment(quote.CoverStartDateTime).format('M/D/YYYY'));
			$('#in-q-coverenddate').val(moment(quote.CoverEndDateTime).format('M/D/YYYY'));
			// $('#in-q-balance').val();
			// $('#in-q-invoicenum').val();

			notes(quote.Notes || []);
		});

		$('#upload-modal [name="file"]').change(function() {
			$(this).parent().siblings('input').val(this.value);
		});

		$('.btn-action').click(function(){
			var tasks = {
				__lockScreen: function() {},
				__lockTrigger: function(trigger) {
					trigger.attr('disabled', true);
				},
				__unlockTrigger: function(trigger) {
					trigger.attr('disabled', false);	
				},
				__toggleEmptyRow: function(table) {
					var rows = $('table.Quotes.'+table+' tbody tr:not(.empty)');
		
					if (rows.length) 
						$('table.Quotes.'+table+' tr.empty').addClass('hidden');
					else $('table.Quotes.'+table+' tr.empty').removeClass('hidden');	
				},
				__tryAction: function() {
					var args = [].slice.call(arguments),
						action = args.shift();
					
					if (typeof this.actions[action] == 'function') {
						return this.actions[action].apply(this, args);
					}
					return false;	
				},
				UploadFields : {
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
				actions: {
					expireQuotes: function(trigger) {
						var quotes = (function() {
								var items = [];
								$('table.Quotes.current input:checked').each(function(){
									return items.push(this);
								});
								return items;
							})(),
							expire = function(inp, callback) {
								var row = $(inp).parents('tr:first');
								$.ajax({
									url: row.data('expire-link'),
									type: 'get',
									dataType: 'json',
									success: function(result) {
										callback.apply(row, [result]);
									},
									error: function(xhr) {
										callback.apply(row, [xhr]);
									}
								});
							},
							success = function(row) {
								row.insertBefore('table.Quotes.expired tr.empty');
								tasks.__toggleEmptyRow('current');
								tasks.__toggleEmptyRow('expired');
								tasks.__unlockTrigger(trigger);
								return alert("Quotes have been set to expired.");
							},
							callback = function(result) {
								if (result.success) {
									return quotes.length >= 1 ? expire(quotes.shift(), callback) : success(this);
								}
								else {
									tasks.__unlockTrigger(trigger);
									return alert("Ops! Something wen't wrong, please try again later.");
								}
							}					

						if (!quotes.length) {
							return alert("Select current Quotes(s) to expire.");
						}

						this.__lockTrigger(trigger);
						return expire(quotes.shift(), callback);
					},
					finalizeQuotes: function(trigger) {
						var quotes = (function() {
								var items = [];
								$('table.Quotes.current input:checked').each(function(){
									return items.push(this);
								});
								return items;
							})(),
							finalize = function(inp, callback) {
								var row = $(inp).parents('tr:first');
								$.ajax({
									url: row.data('finalize-link'),
									type: 'get',
									dataType: 'json',
									success: function(result) {
										callback.apply(row, [result]);
									},
									error: function(xhr) {
										callback.apply(row, [xhr]);
									}
								});
							},
							success = function(row) {
								tasks.__unlockTrigger(trigger);
								return alert("Quote(s) have been finalized.");
							},
							callback = function(result) {
								if (result.success) {
									return quotes.length >= 1 ? finalize(quotes.shift(), callback) : success(this);
								}
								else {
									tasks.__unlockTrigger(trigger);
									if (typeof result['50114'] === 'string')
										return alert(result['50114']);
									return alert("Ops! Something wen't wrong, please try again later.");
								}
							}					

						if (!quotes.length) {
							return alert("Select current Quotes(s) to expire.");
						}

						this.__lockTrigger(trigger);
						return finalize(quotes.shift(), callback);
					},
					compareQuotes: function() {
						var quotes = (function() {
								var items = [];
								$('table.Quotes input:checked').each(function(){
									return items.push($(this).parents('tr:first').data('quote'));
								});
								return items;
							})(),
							modal = $('#compare-modal'),
							columns = function(label, index, quotes) {
								var index = index.split('.'),
									columns = [label];
									
								for (var i in quotes) {
									var data = getData(index, quotes[i], "N/A");	
									if (label == 'Address') {
										data = address(data);
									}
									else if (label.match(/(Period)/)) {
										data = moment(data.date).format('M/D/YYYY');
									}
									columns.push(data);
								}
								return columns;	
							},
							fields = {
								'Reference No.'	 : 'QuoteRefNum',
								'Quote No.'		 : 'QuoteNum',
								'InsuredName'	 : 'Client.InsuredName',
								'Address'		 : 'Address',
								'Classification' : 'Classification',
								'Underwriter'	 : 'Underwriter.CompanyName',
								'Type of Product': 'Product',
								'Period Starting': 'CoverStartDateTime',	
								'Period Ending'  : 'CoverEndDateTime',	
								'Premium'		 : 'Premium',
								'Excess'		 : 'Excess'
							},
							data = [];

						if (!quotes.length)	
							return alert("Please select Quote(s) to compare.");
							
						for(var i in fields) {
							data.push(columns(i, fields[i], quotes));
						}
						
						if (data.length) {
							var head = "<tr><th>Quote Details</th>";
							for(var i in quotes) {
								head += "<th>"+(parseInt(i) + 1)+"</th>"
							}
							head += '</tr>';
							modal.find('thead').html(head);
							var body = modal.find('tbody').html("");
							for(var i in data) {
								var row = "<tr>";
								for (var j in data[i]) {
									row += "<td>"+ data[i][j] +"</td>";
								}
								body.append($(row));
							}

							modal.modal('show').show();
						}	
					},
					createTask : function() {
						var selected = $('table.Quotes.current tr.success');
						var quote = selected.data('quote');
								
						if (!quote) return alert("Please select an item on a Quote first.");

						alert("To be added soon...");
					},
					assignFields: function() {
						var head = $('#header-fields');
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
					uploadQuotes: function(trigger) {
						var me = $(trigger);
						var form = me.parents('form:first');					
						var data = new FormData();
						var files = form.find('[name="file"]').get(0).files || {};
						var that = this;

						var assignFields = function(headings, body) {
							var head = $('#header-fields').removeClass('hidden');
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
									s.val('[Quote][QuoteNum]').attr('readonly', 1);
								}
								else if (heading == 'invoice #') {
									s.val('[Invoice][InvoiceNum]').attr('readonly', 1);
									console.log(s);
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

						this.__lockTrigger(trigger);

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
								that.__unlockTrigger(trigger);
							},
							error: function(xhr) {
								alert("Ops! Something wen't wrong please try again later.");
								console.log(xhr.responseText);
								that.__unlockTrigger(trigger);
							}
						})

						return false;
					}
				}
								
			},
			me = $(this),
			action = me.data('task');

			return tasks.__tryAction(action, me);
		});
	});
</script>