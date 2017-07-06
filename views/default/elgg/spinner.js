define(function (require) {
	var $ = require('jquery');

	var active = false;
	var SHOW_DELAY = 20;

	$('body').append('<div class="elgg-spinner"><div class="elgg-ajax-loader"></div><div class="elgg-spinner-text elgg-subtext"></div></div>');

	var spinner = {
		/**
		 * Activate the spinner (will appear after 20ms).
		 *
		 * @param {String} text Text to display below spinner (will be escaped)
		 */
		start: function (text) {
			active = true;
			
			spinner.clearText();

			setTimeout(function () {
				if (active) {
					$('body').addClass('elgg-spinner-active');
					$(spinner).triggerHandler('_testing_show');
				}
			}, SHOW_DELAY);

			if (typeof text === 'string') {
				spinner.setText(text);
			} else {
				spinner.setText('');
			}
		},

		/**
		 * Deactivate the spinner
		 */
		stop: function () {
			active = false;
			$('body').removeClass('elgg-spinner-active');
		},

		/**
		 * Set the text on a displayed spinner.
		 *
		 * @param {String} text Text to display below spinner (will be escaped)
		 */
		setText: function (text) {
			$('.elgg-spinner .elgg-spinner-text').text(text);
		},

		/**
		 * Remove the text on a displayed spinner.
		 */
		clearText: function () {
			$('.elgg-spinner .elgg-spinner-text').html('');
		}
	};

	return spinner;
});

