/**
 * Toggle menu module
 *
 * @module elgg/menus/toggle
 */
define(function (require) {

	var elgg = require('elgg');

	var toggle = {

		init: function () {
			$(document).on('click', '.elgg-menu-item-has-toggle > a', function (e) {

				e.preventDefault();

				var $trigger = $(this);
				var $target = $trigger.siblings('.elgg-child-menu').eq(0);

				var duration = $target.data('toggleDuration') || 'fast';

				$target.slideToggle(duration, function () {
					if ($target.is(':visible')) {
						$trigger.addClass('elgg-menu-opened')
								.removeClass('elgg-menu-closed');
						$trigger.parent().addClass('elgg-state-selected');
					} else {
						$trigger.addClass('elgg-menu-closed')
								.removeClass('elgg-menu-opened');
						$trigger.parent().removeClass('elgg-state-selected');
					}
				});
			});

			toggle.init = elgg.nullFunction;
		}
	};

	toggle.init();

	return toggle;
});

