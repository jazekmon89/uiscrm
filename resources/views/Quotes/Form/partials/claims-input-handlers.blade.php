<script>
	$(document).ready(function() {
		$('.claims-trigger').bind('change ifToggled', function() {
			var me = $(this);
			if (!me.is(':checked')) return;
			if (me.val() === 'Y') $(me.data('target')).removeClass('hidden');
			else $(me.data('target')).addClass('hidden');
		}).change();

		$('.amnt-paid input, .amnt-outstanding input').on('change', function() {
			var me = $(this);
			var parent = me.parent('.form-group');
			var sibling = parent.siblings('.amnt-outstanding,.amnt-paid');
			var paidOrOT = parent.find('input').val() || 0;
			var otOrPaid =	sibling.find('input').val() || 0;
			
			parent.siblings('.total').text(parseInt(paidOrOT) + parseInt(otOrPaid));
		});
	});
</script>	