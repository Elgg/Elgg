define(function (require) {
	var $ = require('jquery'),
		elgg = require('elgg'),

		num_running = 0,
		$indicator;

	/**
	 * @constructor
	 *
	 * @param {Object} opts Options with keys:
	 *
	 *   $wait    : {jQuery} (optional) If provided, the class .elgg-wait-cursor will be present
	 *                       on this element(s) until stop() is called.
	 *   fadeOpts : {Object} (optional) Options for the $.fadeOut method
	 */
	function Spinner(opts) {

		opts = opts || {};
		opts.$wait = opts.$wait || $();
		opts.fadeOpts = opts.fadeOpts || {};

		var is_running = false;

		if (!(opts.$wait instanceof $)) {
			throw new Error("If given, opts.$wait must be an instance of jQuery");
		}
		if (!$.isPlainObject(opts.fadeOpts)) {
			throw new Error("If given, opts.fadeOpts must be a plain object");
		}

		/**
		 * Start this instance of the spinner. If no instances were running, the progress
		 * indicator will be displayed.
		 */
		this.start = function () {
			if (is_running) {
				return;
			}

			is_running = true;
			opts.$wait.addClass('elgg-wait-cursor');

			if (!num_running) {
				if ($indicator) {
					$indicator.show();
				} else {
					$indicator = $('<div class="elgg-spinner elgg-ajax-loader"></div>');
					$indicator.appendTo('body');
				}
			}
			num_running += 1;
		};

		/**
		 * Stop this instance. The indicator will be removed once all instances are stopped.
		 */
		this.stop = function () {
			if (!is_running) {
				return;
			}

			is_running = false;
			opts.$wait.removeClass('elgg-wait-cursor');

			num_running = Math.max(0, num_running - 1);
			if (!num_running) {
				$indicator.fadeOut(opts.fadeOpts);
			}
		};
	}

	/**
	 * Reset global state for testing purposes
	 *
	 * @private
	 */
	Spinner._reset = function () {
		$('.elgg-spinner').remove();
		$indicator = null;
		num_running = 0;
	};

	return Spinner;
});
