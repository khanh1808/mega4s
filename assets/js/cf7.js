(function ($) {
	"use strict";

	$(document).ready(function() {
		$(document).on('wpcf7mailsent', function(e) {
		// $(document).on('wpcf7mailfailed', function(e) {

			$('#open_notice').trigger('click');

		})

		$('.close_popup').on('click', function(event) {
			event.preventDefault();

			$('#popup_notice .mfp-close').trigger('click');
		});
	});

})(jQuery);