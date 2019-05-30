<script>
	client = {!! json_encode($client) !!},
			recommended = {!! json_encode($recommended) !!},
			recommendations = {!! json_encode($recommendations) !!}
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
			};

		var toggleFullView = function () {
			$('.cover-selection-form').toggleClass('expanded');
		},
			bindExpandButton = function() {
				var me = $(this);
				if (me.data('table') === 'recommendations') {
					var row = me.parents('tr:first');
					var recommendation = row.data('recommendation') || {};

					if (recommendation.Notes)
						$('#policy-notes').val(recommendation.Notes);
					// $(".notes button.dismiss").addClass("hidden");
					$(".notes button.update").removeClass("hidden");
				}
				else {
					var icon = $(this).find('.fa');	
					if ($('#policy-notes').is(":visible")) {
						icon
							.addClass('fa-expand')
							.removeClass('fa-compress');
					}
					else {
						icon
							.removeClass('fa-expand')
							.addClass('fa-compress');
					}
				}
				toggleFullView();
			},
			bindDeleteButton = function(row) {
				var row = $(this);

					if (row.hasClass('success')) return;
					row.addClass('success');
					row.siblings().removeClass('success');
					toggleButton($('.buttons .delete'), false);
			},
			bindSelectButton = function(row) {
				var row = $(this);
					row.addClass('active');
					row.siblings().removeClass('active');
					toggleButton($('.buttons .select'), false);
					$('.cover-selection-form').addClass('expanded');
			},
			toggleButton = function(button, toggle) {
				var btn = $(button)
					.attr('disabled', toggle)
				if (toggle)
					btn.addClass('disabled')
				else btn.removeClass('disabled')

			};
		$('.buttons .delete').click(function() {
			var row = $('.list tbody tr.success');
			var recommendation = row.data('recommendation') || {};

			if (!row.length || !recommendation.ClientRecommendationID) {
				return alert("Please select a recommended type of cover to delete.");
			}

			if (recommendation.PolicyType.Name === 'WorkersComp') 
				return alert("You can't remove Worker Compensation Policy.");

			var btn = this;
			toggleButton(this, true);

			$.ajax({
				url: '{{ route('client.removerecommendation', [$client->ClientID]) }}',
				data: {
					ClientRecommendationID: recommendation.ClientRecommendationID
				},
				success: function(result) {
					row.remove();
					$('#policy-'+ recommendation.PolicyTypeID).removeClass('disabled');

					for(var i=0;i<recommended.length;i++) {
						if (recommended[i].PolicyTypeID === recommendation.PolicyTypeID) {
							recommended.splice(i, 1);
							break;
						}
					}

					if (typeof drawWheel === 'function')
						drawWheel();
				},
				error: function(xhr) {
					alert("Ops! Something went wrong please try again later.");
				}
			})

			return false;
		});
		$('.list .scroll tbody tr:not(.hidden)').click(bindDeleteButton);
		$('.notes button.update').click(function(){
			var row = $('.list tbody tr.success');
			var recommendation = row.data('recommendation') || {};

			if (!row.length || !recommendation.ClientRecommendationID) {
				return alert("Please select a recommended type of cover to delete.");
			}

			var btn = this;
			toggleButton(this, true);

			$.ajax({
				url: '{{ route('client.addrecommendation', [$client->ClientID]) }}',
				data: {
					ClientRecommendationID: recommendation.ClientRecommendationID,
					Notes: $('#policy-notes').val(),
					PolicyTypeID: recommendation.PolicyTypeID
				},
				success: function(result) {
					if (result.ClientRecommendationID) {
						recommendation.ClientRecommendationID = result.ClientRecommendationID;
						row.find(".summary").html(result.Notes.summary || "");
						recommendation.Notes = result.Notes.full;
						row.data('recommendation', recommendation);
						toggleFullView();
					}
				},
				error: function(xhr) {
					alert("Ops! Something went wrong please try again later.");
				}
			})
		});
		$('.buttons .select').click(function () {
			var row = $('.cover-options-list li.active');
			var policy = row.data('policy');
			var recommendation = policy !== null ? recommendations[policy] : null;

			if (!row.length || !recommendation || recommendation.PolicyTypeID === null) {
				return alert("Please select a recommended type of cover to delete.");
			}

			toggleButton(this, true);
			var data = {
				PolicyTypeID: recommendation.PolicyTypeID,
				Notes: $('#policy-notes').val()
			};

			$.ajax({
				url: '{{ route('client.addrecommendation', [$client->ClientID]) }}',
				data: data,
				success: function(result) {
					
					if (result.ClientRecommendationID) {
						var ref = $('.list table tbody tr.hidden');
						row	.removeClass('active')
							.addClass('disabled');
						var rec = ref.clone();
						
						rec.children('.displayText').html(recommendation.DisplayText);
						rec.find('.notesText .summary').html(result.Notes.summary);

						rec.find('.btn-expand')
							.data('table', 'recommendations')
							.click(bindExpandButton);

						result.Notes = result.Notes.full;
						result.policy = $.extend({}, recommendation);
						result.PolicyType = $.extend({}, recommendation);
						
						rec.data('recommendation', result);
						rec.insertBefore(ref);
						rec.click(bindDeleteButton);
						rec.removeClass('hidden');

						toggleButton(this, false);

						$('.cover-selection-form').removeClass('expanded');
						
						result.active = recommendation.active || 0;
	
						recommended.push($.extend({}, result));

						if (typeof drawWheel === 'function')
							drawWheel();
					}
				},
				error: function(xhr) {
					alert("Ops! Something went wrong please try again later.");
				}
			})
		});
		$('#cover-option-search').on('keyup', function() {
			var items = $('.cover-options-list li');
			var search = $(this).val().trim();

			if (!search) return items.removeClass('hidden');

			items.each(function() {
				var me = $(this);
				var text = me.text().trim();

				if (text.match(new RegExp("("+search.split(" ").join("|")+")", "i"))) {
					me.removeClass('hidden');
				}
				else {
					me.addClass('hidden');
				}
			});
		});
		$('.btn-expand').click(bindExpandButton);
		
		$('.cover-options-list li a').click(function() {
			var parent = $(this).parent();
			if (parent.hasClass('disabled') || parent.hasClass('active'))
				return false;
			$('#policy-notes').val("");
			$(".notes button.dismiss").removeClass("hidden");
			$(".notes button.update").addClass("hidden");
			bindSelectButton.apply(parent.get(0));

			return false;
		});
	});
	// comment
</script>
