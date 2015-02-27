define(function (require) {
	var $ = require('jquery');

	$('body').append('<div class="elgg-spinner"><div class="elgg-ajax-loader"></div></div>');

	return {
		start: function () {
			$('body').addClass('elgg-spinner-active');
		},

		stop: function () {
			$('body').removeClass('elgg-spinner-active');
		}
	};
});
