<script>
	jQuery(document).ready(function($) {
		var getData = function(depth, raw, def) {
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
				rfq 	= me.data('rfq') || {},
				toggleActive = function(row) {
					if (row.prop('tagName') !== 'TR')
						row = row.parents('tr:first');

					row.siblings('.success').removeClass('success');
					row.addClass('success');
				};
				
			toggleActive(me);
			console.log(rfq);	
			return;
			$('#in-q-datelodge').val(rfq.Quote.QuoteRefNum);
			$('#in-q-referencenum').val(policy.PolicyNum || "");
			$('#in-q-quotenum').val(quote.Quote.QuoteNum || "");
			$('#in-q-insuredname').val(rfq.InsuredName || "");
			$('#in-q-address').val(address(quote.Address));
			
			$('#in-q-classification').val(quote.Quote.Classification);
			$('#in-q-underwriter').val(writer.CompanyName || "");
			$('#in-q-product').val(quote.Quote.Product || "");
			$('#in-q-premium').val(quote.Quote.Premium || "");
			// $('#in-q-excess').val();

			$('#in-q-coverstartdate').val(moment(quote.Quote.CoverStartDateTime.date).format('M/D/YYYY'));
			$('#in-q-coverenddate').val(moment(quote.Quote.CoverEndDateTime.date).format('M/D/YYYY'));
			// $('#in-q-balance').val();
			// $('#in-q-invoicenum').val();

			notes(quote.Notes || []);
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
								$('table.Quotes input:checked').each(function(){
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
								'Reference No.'	 : 'Quote.QuoteRefNum',
								'Policy No.'	 : 'Quote.Policy.PolicyNum',
								'Quote No.'		 : 'Quote.QuoteNum',
								'InsuredName'	 : 'RFQ.InsuredName',
								'Address'		 : 'Address',
								'Classification' : 'Quote.Classification',
								'Underwriter'	 : 'Underwriter.CompanyName',
								'Type of Product': 'Quote.Product',
								'Period Starting': 'Quote.CoverStartDateTime',	
								'Period Ending'  : 'Quote.CoverEndDateTime',	
								'Premium'		 : 'Quote.Premium',
								'Excess'		 : 'Quote.Excess'
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
					}	
				}
								
			},
			me = $(this),
			action = me.data('task');

			return tasks.__tryAction(action, me);
		});
	});
</script>