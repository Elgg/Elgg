define(function (require) {
	var $ = require('jquery');

	var active = false;
	var SHOW_DELAY = 20;

	$('body').append('<div class="elgg-spinner"><div class="elgg-ajax-loader"></div><div class="elgg-spinner-text elgg-subtext"></div></div>');

	return {
		start: function (text) {
			active = true;
			
			this.clearText();
			
			setTimeout(function () {
				if (active) {
					$('body').addClass('elgg-spinner-active');
				}
			}, SHOW_DELAY);
			
			this.setText(text);
		},

		stop: function () {
			active = false;
			$('body').removeClass('elgg-spinner-active');
		},
		
		setText: function (text) {
			$('.elgg-spinner .elgg-spinner-text').text(text);
		},
		
		clearText: function () {
			$('.elgg-spinner .elgg-spinner-text').html('');
		},
	};
});
