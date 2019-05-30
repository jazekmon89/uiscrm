<script>
	var incrementInputIndexes = function(repeated, baseIndex) {
		var match = repeated.data('repeat-match');
		if (!match) return repeated;
		function incrementID(ID, baseKey, index) {
			var baseKey = baseKey.replace(/(\[\]|\.|\[|\])/, '-');

			var indexBase = ID.replace(baseKey, "");
			return baseKey + indexBase.replace(/^\-(\d+)/, "-" + index);
		}

		function incrementName(Name, baseKey, index) {
			var segments = baseKey.split('.');
			var baseKey = segments.shift();
			for(var i in segments) baseKey += '\[' + segments[i] + '\]';
			var indexBase = Name.replace(baseKey, "");
			return baseKey + indexBase.replace(/^\[(\d+)\]/, "[" + index + "]");
		}
		function incrementToggleOrRemove(Href, baseKey, index) {
			var baseKey = baseKey.replace(/(\[\]|\.|\[|\])/, '-');
			var Href = Href.substring(1);
			var indexBase = Href.replace(new RegExp("^("+baseKey+"-)"), "");
			return '#' + baseKey + indexBase.replace(/^(\d+)/, "-" + index);
		}
		function incrementFormPath(path, baseKey, index) {
			var indexPath = path.replace(baseKey, "");
			return baseKey + indexPath.replace(/^\.(\d+)/, '.'+index);
		}
		repeated.find('label, input, select, textarea').each(function() {
			var me = $(this),
				keyValue = me.attr('type') === 'radio' || me.attr('type') === 'checkbox';
			
			me.siblings('.label.label-alert').remove();
			if (keyValue) me.prop('checked', false);
			else if (me.attr('type') !== 'hidden' && !keyValue) me.val("");
			if (me.attr('id')) me.attr('id', incrementID(me.attr('id'), match, baseIndex));
			if (me.attr('for')) me.attr('for', incrementID(me.attr('for'), match, baseIndex));
			if (me.attr('name')) me.attr('name', incrementName(me.attr('name'), match, baseIndex));
		});
		repeated.find('[data-toggle], [data-remove], [data-toggle-area], [data-formpath]').each(function() {
			var me = $(this);
			if (me.attr('data-toggle')) me.attr('href', incrementToggleOrRemove(me.attr('href'), match, baseIndex));
			if (me.attr('data-remove')) me.attr('data-remove', incrementToggleOrRemove(me.attr('data-remove'), match, baseIndex));
			if (me.attr('data-toggle-area')) me.attr('id', incrementID(me.attr('id'), match, baseIndex));
			if (me.attr('data-formpath')) me.attr('data-formpath', incrementFormPath(me.attr('data-formpath'), match, baseIndex))
		});

		repeated.attr('id', incrementID(repeated.attr('id'), match, baseIndex));
		repeated.attr('data-repeat-index', baseIndex);

		repeated.find('.datetimepicker').each(function() {
			var me = $(this);
				format = me.data('date-format') || 'D/M/YYYY';

			me.datetimepicker({
				format: format,
				viewMode: 'years'
			});
		});
		
		// repeated.find('td.total').html("");

		repeated.find('.isLinked').each(function() {
			var me = $(this);
			var linked = null;
			var dataLinked = me.data('linked') || {};
			var formPath = me.data('formpath');

			for(var i in dataLinked) {
				findAndLinkQuestion(i, formPath, me.data('expected', dataLinked[i]), repeated);
			}
		});

		// repeated.find('.amnt-paid input, .amnt-outstanding input').on('keyup change', function() {
		// 	var me = $(this);
		// 	var parent = me.parent('.form-group');
		// 	var sibling = parent.siblings('.amnt-outstanding,.amnt-paid');
		// 	var paidOrOT = parent.find('input').val() || 0;
		// 	var otOrPaid =	sibling.find('input').val() || 0;
			
		// 	parent.siblings('.total').text(parseInt(paidOrOT) + parseInt(otOrPaid));
		// });

		repeated.find('.panel-collapse').collapse('show');

		return repeated;
	}
	$(document).ready(function() {
		$(document).on('click', '[data-remove]', function() {
			var button = $(this);
			var me = $(button.attr('data-remove'));
			if (me.siblings(':not('+button.attr('data-remove')+')').length) me.fadeOut().remove();
			return false;
		});
		$(document).on('click', '[data-jtoggle]', function() {
			var button = $(this);
			var me = $(button.attr('data-jtoggle')).toggleClass('in');
			return false;
		});

		var bindRepeatClick = function(e) {
			var me = $(this);
			if (me.hasClass('disabled'))
				return false;

			var me = me.addClass('disabled');
			var toRepeat = me.data('repeat') || false;
			var container = $(me.data('repeat-container')) || false;		
			var repeat = container.find(toRepeat+':last');

			if (!repeat.length || !container.length)
				return false;

			var title = repeat.data('repeat-title') || false;	
			
			var baseIndex = (repeat.data('repeat-index') || 0) + 1;
			var clone = repeat.clone();	
			var title = clone.find(title);

			incrementInputIndexes(clone, baseIndex);

			if (title.length) {
				var html = title.html();
				var num = html.match(/\#(\d+)/);
				title.html(html.replace(/\#(\d+)/, '#' + ((num ? parseInt(num[1]) : baseIndex) + 1)));
			}
			clone.find('.has-error').removeClass('has-error');
			clone.insertAfter(repeat);
			me.removeClass('disabled');
			return false;	
		}
		$('button.btn-repeat:not(.disabled)').unbind('click', bindRepeatClick).bind('click', bindRepeatClick);
	});
</script>