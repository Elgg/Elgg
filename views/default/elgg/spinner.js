define(function (require) {
	var $ = require('jquery');

	var active = false;
	var SHOW_DELAY = 20;

	$('body').append('<div class="elgg-spinner"><div class="elgg-ajax-loader"></div></div>');

	var module = {
		start: function () {
			active = true;
			setTimeout(function () {
				if (active) {
					$('body').addClass('elgg-spinner-active');
					$(module).triggerHandler('_testing_show');
				}
			}, SHOW_DELAY);
		},

		stop: function () {
			active = false;
			$('body').removeClass('elgg-spinner-active');
		}
	};

	return module;
});
