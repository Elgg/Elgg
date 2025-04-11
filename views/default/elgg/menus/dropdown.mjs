/**
 * Dropdown menu module
 */

import 'jquery';
import popup from 'elgg/popup';

var dropdown = {

	init: function () {
		// handles clicking on a menu item that has a dropdown menu
		$(document).on('click keydown', '.elgg-menu-item-has-dropdown > a', function (e) {
			if (e.type === 'keydown' && e.key !== 'Enter') {
				return;
			}
			
			var $trigger = $(this);
			
			// keep track of eventType for popup 'open' event callback
			$trigger.data('eventType', e.type);
			
			if ($trigger.attr('data-popup-trigger-closed')) {
				// popup was closed by clicking on this trigger
				$trigger.removeAttr('data-popup-trigger-closed');
				return;
			}
			
			if ($trigger.data('dropdownMenu')) {
				var $target = $trigger.data('dropdownMenu');
			} else {
				var $target = $trigger.siblings('.elgg-child-menu').eq(0);
				$trigger.data('dropdownMenu', $target);
				$target.on('open', function () {
					$trigger.addClass('elgg-menu-opened')
							.removeClass('elgg-menu-closed')
							.prop('ariaExpanded', true);
					$trigger.parent().addClass('elgg-state-selected');
					
					// set focus on div if mouse clicked so first element does not get focussed by focus trap
					if ($trigger.data().eventType === 'click') {
						$target.attr('tabindex', '-1').focus();
					}
				});

				$target.on('close', function () {
					$trigger.addClass('elgg-menu-closed')
							.removeClass('elgg-menu-opened')
							.prop('ariaExpanded', false);
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

		dropdown.init = function() {};
	}
};

dropdown.init();

export default dropdown;
