define(function (require) {
	var $ = require('jquery');

	var active = false;
	var SHOW_DELAY = 20;

	$('body').append('<div class="elgg-spinner"><div class="elgg-ajax-loader"></div></div>');

	return {
		start: function () {
			active = true;
			setTimeout(function () {
				if (active) {
					$('body').addClass('elgg-spinner-active');
				}
			}, SHOW_DELAY);
		},

		stop: function () {
			active = false;
			$('body').removeClass('elgg-spinner-active');
		}
	};
});
