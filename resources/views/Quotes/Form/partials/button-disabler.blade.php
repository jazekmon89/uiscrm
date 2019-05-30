<script>
	$(document).ready(function() {
		$(document).on('click', '.btn.submit, button.button, a.btn', function() {
			if (!$(this).attr('disabled'))
				$(this).attr('disabled', true);
			if (this.tagName !== 'A')
			$('form').submit();
		});

		// TODO: remove disable is detect changes in input values
	});
</script>