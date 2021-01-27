define(['jquery', 'elgg', 'jquery-ui/widgets/datepicker'], function ($, elgg) {

	// the language module may need loading
	var i18n_ready = $.Deferred();
	if (elgg.get_language() === 'en') {
		i18n_ready.resolve();
	} else {
		require(['jquery-ui/i18n/datepicker-' + elgg.get_language()], function () {
			i18n_ready.resolve();
		}, function () {
			// if load fails (e.g. lang code mismatch), carry on with English
			i18n_ready.resolve();
		});
	}

	var datepicker = {
		/**
		 * Initialize the date picker on elements defined by the selector
		 *
		 * If the class .elgg-input-timestamp is set on the element, the onSelect
		 * method converts the date text to a UNIX timestamp in seconds. That value is
		 * stored in a hidden element and submitted with the form. Note that the UNIX
		 * timestamp is normalized to start of the day at UTC, so you may need to use
		 * timezone offsets if you expect a different timezone. Timestamp is determined
		 * by the selected values of the datepicker instance, and is therefore agnostic
		 * to the dateFormat option.
		 *
		 * @param {string} selector Element selector
		 * @return void
		 * @requires jqueryui.datepicker
		 */
		init: function (selector) {
			if (!$(selector).length) {
				return;
			}
			var defaults = {
				dateFormat: 'yy-mm-dd',
				nextText: '&#xBB;',
				prevText: '&#xAB;',
				changeMonth: true,
				changeYear: true
			};

			$(selector).each(function () {
				var $elem = $(this);
				var opts = $elem.data('datepickerOpts') || {};
				opts = $.extend({}, defaults, opts);

				opts.onSelect = function (dateText, instance) {
					if ($(this).is('.elgg-input-timestamp')) {
						timestamp = '';
						if (dateText.length) {
							// convert to unix timestamp
							var timestamp = Date.UTC(instance.selectedYear, instance.selectedMonth, instance.selectedDay);
							timestamp = timestamp / 1000;
						}
						$('input[rel="' + this.id + '"]').val(timestamp);
					}
				};

				// defer until language loaded
				i18n_ready.then(function () {
					$elem.datepicker(opts);
					$elem.on('keyup', function(event) {
						switch (event.keyCode) {
							case $.ui.keyCode.DELETE:
							case $.ui.keyCode.BACKSPACE:
								$.datepicker._clearDate(event.target);
								break;
						}
					});
				});
			});
		}
	};

	return datepicker;
});

