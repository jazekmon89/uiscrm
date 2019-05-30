<script>
	String.prototype.strpos = function(needle, offset) {
		var i = this.indexOf(needle, offset || 0)
		return i === -1 ? false : i;
	}
	$(document).ready(function() {
		var covers = {!! json_encode(config('covers')) !!},
			eq = {
				bi: function(annual) {
					var annual = $('#qid-'+annual+' input').val() || 0;
					
					return annual ? Math.ceil(parseInt(annual) * .6) : '';
				},
				rv: function(return_value) {
					return return_value;
				},
				csi: function(b) {
					b = b ? b / 1000 : 0;
					if (covers.sets) {
						for(var i in covers.sets) {
							var r = i.split('-');
							if (b >= parseInt(r[0]) && (r[1] == '*' || b <= parseInt(r[1]))) {
								return r;
							}
						}
					}
					return null;
				}
			};

		if (covers.trigger.input) {
			$('#qid-'+covers.trigger.input+' input').keyup(function() {		
				var me = $(this);
				var so = me.val();
				
				if (covers.trigger.listeners) {
					for(var i in covers.trigger.listeners) {
						var inp = $('#qid-'+covers.trigger.listeners[i]+' input');
						inp.val(so);
						if (covers.trigger.listeners[i] === covers.trigger.si)
						{
							var si = eq.csi( so );
							var m = si ? si[1] && si[1] !== '*' ? si[1] : si[0] : 0;
							inp
								.data('set', si)
								.trigger('change');
						}
					}
				}
			});	
		}
		if (covers.trigger.si && covers.sets) {
			$('#qid-'+covers.trigger.si+' input').change(function() {
				var me = $(this);
				var set = me.data('set') || [];

				if (!set) {
					var si = eq.csi( me.val() );
					set = si ? si[1] && si[1] !== '*' ? si[1] : si[0] : 0;
				}
				set = set.join('-');
				if (covers.sets[set]) {
					for(var i in covers.sets[set]) {
						var b = $('#qid-'+i);
						var v = covers.sets[set][i].toString();
						
						if (v.strpos(':')) {
							var fa = v.split(':'),
								args = fa[1].match(/([a-z\_0-9]+)(?:\[(.*)\])$/i) || [];
							
							if (args && args.length) {
								fa[1] = args[1];
								args = args[2].split(',');
							}
							if (typeof eq[fa[1]] === 'function') {
								v = eq[fa[1]].apply(this, args);
							}
						}
						b.find('input').val(v);
						b.find('select').val(v);
					}
				}
			});
		}

		$('#Covers-Cover-optional-trigger').bind('change ifToggled', function() {
			var me = $(this);	
				if (me.is(':checked')) me.parents('.hidden-trigger').siblings('.covers-cover').removeClass('hidden');
				else me.parent().siblings('.covers-cover').addClass('hidden');
		}).change();
		$('#Covers-Set').bind('change', function() {
			var me = $(this);
			$('.cover-set-levels .cover-set-level').addClass('hidden');
			$('#cover-set-level-'+$(this).val()).removeClass('hidden');
		}).change();
		function lossOfIncome(){
			if($(".Boolean.LossOfIncome input:checked").val() == 'Y')
				$(".Boolean.LossOfIncome").next().show();
			else
				$(".Boolean.LossOfIncome").next().hide();
		}
		lossOfIncome();
		$(".Boolean.LossOfIncome input").on("click", function(){
			if($(this).val() == 'Y')
				lossOfIncome(true);
			else
				lossOfIncome(false);
		});
	});
</script>	
