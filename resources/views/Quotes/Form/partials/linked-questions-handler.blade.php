<script id="linked-questions">
	var findAndLinkQuestion = function(qid, baseKey, source, container) {
			var formPath = function(qid, baseKey) {
				var base = baseKey.split('.');
				var strbase = base.shift();

				base.push(qid);

				// make input name for the linked question
				for(var i in base) strbase += '['+ base[i] +']';
				return strbase;	
			}

			var resetLinkedFields = function(el){
				/*if(!$(el).find('.address-wrapper .no-clear').length){
					if($(el).find('select').length && $(el).find('select').not(":visible").length)
						$(el).find('select').val('');
					if(($(el).find('input[type=checkbox]').length && $(el).find('input[type=checkbox]').not(":visible").length) || ($(el).find('input[type=radio]').length && $(el).find('input[type=radio]').not(":visible").length))
						$(el).find('input').prop('checked', false);
					if($(el).find('input[type=text]').length && $(el).find('input[type=text]').not(":visible").length)
						$(el).find('input').val('');
					if($(el).find('textarea').length && $(el).find('textarea').not(":visible").length)
						$(el).find('textarea').val('');
				}*/
			}

			var toggleSources = function() {
				var me = $(this), val = null;
				
				var getChexboxValue = function(name) {
					// make sure we get the real value if the checkbox value is not 
					// the same as the index same we set the value also as index in the backend
					var base = name.match(/\[([a-z0-9\-]+)\]$/i);
					return base ? base[1] : null;
				}
				// since we bind to all input we skip unchecked ones
				if (me.attr('type') === 'radio' && !me.is(':checked')) return;

				// for checkbox deny if not checked 
				if (me.attr('type') === 'checkbox' && !me.is(':checked')) 
					val = '';
				else val = me.val();

				var sources = me.data('sources') || [];

				sources.forEach(function(el, index) {
					var source = $(el), ret = false,
						expected = source.data('expected') || null,
						links = source.data('links');
					expected = !$.isArray(expected) ? [expected] : expected;
					if (-1 !== $.inArray(val, expected) && !me.parents('.row:first').hasClass('hidden')) {
						ret = source.removeClass('hidden')
									.attr('disabled', false)
									.find('input, select').change();
						resetLinkedFields(el);
						return ret;
					}
					else if (links.length <= 1 || me.parents('.row:first').hasClass('hidden')) {
						ret =  source.addClass('hidden')
									.attr('disabled', true)
									.find('input, select').change();
						resetLinkedFields(el);
						return ret;
					}
					var cnt = 0;
					links.forEach(function(e, i) {
						var el = function(e) {
							var n = null;
							e.each(function() {
								var ee = $(this);
								if ((ee.attr('type') == 'radio' 
									|| ee.attr('type') == 'checkbox')
									&& ee.is(":checked")) {
									n = this;
								}
							});
							return $(n);
						}
						var e = el(e), c = e.val() || null;

						// linked question will show if any of the links are matched 
						// to expexted value otherwise hide the element
						if (-1 === $.inArray(c, expected)) cnt++;
					});
					if (cnt == links.length) 
						source.addClass('hidden')
							  .attr('disabled', true)
							  .find('input, select').change();
					resetLinkedFields(el);
				});
			}
			var nodeEl = function(name) {
				return container.find('[name="'+name+'"]');
			}
			var checkboxes = function(name, source, node) {

				// checkboxes are specials since we set the key/ID as index
				// but since we also set the value same as index so gtting the value 
				// is not an issue
				name += '['+ (source.data('expected') || '') +']';
				return {
					name: name,
					node: nodeEl(name)
				}
			}

			var container = container || $('body');
			var name = formPath(qid, baseKey);
			var node = nodeEl(name);
			
			if (!node.length) {
				var nn = checkboxes(name, source);
				name = nn.name;
				node = nn.node;
			}

			var getLinkedRegistry = function(node) {
				if (node.data('Linked')) return node.data('Linked');

				var linked = {
					qid: qid,
					baseKey: baseKey,
					name: name,
					node: node,
					el: function() {
						if (this.node) return this.node;
						return this.node = node;
					},
					isLinked: function() {
						return this.el().length;
					},
					sources: function() {
						return this.el().data('sources') || []
					},
					addSource: function(el) {
						var node = this.el();
						var data = node.data('sources') || [];

						data.push(el);
						node.data('sources', data);

						var links = el.data('links') || [];
						links.push(node);
						el.data('links', links);

						return this;
					}
				};
				node.data('Linked', linked);
				return linked;
			};
			var linked = getLinkedRegistry(node).addSource(source);
			node.bind('change ifToggled', toggleSources).change();
			return node;
		}

	$(document).ready(function() {
		$('.isLinked').each(function() {
			var me = $(this);
			var linked = null;
			var dataLinked = me.data('linked') || {};
			var formPath = me.data('formpath');
			for(var i in dataLinked) {
				findAndLinkQuestion(i, formPath, me.data('expected', dataLinked[i]));
			}
		});
	});
</script>