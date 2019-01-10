/**
 * Dropdown menu module
 *
 * @module elgg/menus/dropdown
 */
define(function (require) {

	var elgg = require('elgg');
	var popup = require('elgg/popup');

	var dropdown = {

		init: function () {
			// handles clicking on a menu item that has a dropdown menu
			$(document).on('click', '.elgg-menu-item-has-dropdown > a', function (e) {
				var $trigger = $(this);
				if ($trigger.data('dropdownMenu')) {
					var $target = $trigger.data('dropdownMenu');
				} else {
					var $target = $trigger.siblings('.elgg-child-menu').eq(0);
					$trigger.data('dropdownMenu', $target);

					$target.on('open', function () {
						$trigger.addClass('elgg-menu-opened')
								.removeClass('elgg-menu-closed');
						$trigger.parent().addClass('elgg-state-selected');
					});

					$target.on('close', function () {
						$trigger.addClass('elgg-menu-closed')
								.removeClass('elgg-menu-opened');
						$trigger.parent().removeClass('elgg-state-selected');
					});
				}

				if (!$trigger.length || !$target.length) {
					return;
				}

				e.preventDefault();

				$target.addClass('elgg-menu-hover');
				var position = $target.data('position') || {
					at: 'center bottom',
					my: 'center top',
					collision: 'fit fit'
				};
				position.of = $trigger;

				popup.open($trigger, $target, position);
			});
			
			// if an anchor also has its own link the text acts as the link, the before pseudo element handles the toggle
			$(document).on('click', '.elgg-menu-item-has-dropdown > a > .elgg-anchor-label', function (e) {
				var $anchor = $(this).closest('a');
				var href = $anchor.attr('href');
				
				if ($anchor.hasClass('elgg-non-link') || !href) {
					return;
				}
				
				document.location = href;
				
				e.preventDefault();
				e.stopImmediatePropagation();
			});

			dropdown.init = elgg.nullFunction;
		}
	};

	dropdown.init();

	return dropdown;
});
