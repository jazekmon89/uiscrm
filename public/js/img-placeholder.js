(function($) {

	$(document).ready(function() {
		var placeholder = {};
		$('.img-placeholder').each(function() {
			var placeholder = $(this);
			var src = placeholder.data('src');

			setTimeout(function() {
				$.get(src, function(result) {
					var image = new Image;
					image.src = src;

					placeholder.html(image);
					
				}).done(function(){

				});
			}, 100);
		});
	});

})(jQuery);