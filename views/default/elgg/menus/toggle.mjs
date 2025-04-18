/**
 * Toggle menu module
 */

import 'jquery';

var toggle = {
	init: function () {
		// handles clicking on a menu item that has toggleable childmenu
		$(document).on('click', '.elgg-menu-item-has-toggle > a', function (e) {

			e.preventDefault();

			var $trigger = $(this);
			var $target = $trigger.siblings('.elgg-child-menu').eq(0);

			var duration = $target.data('toggleDuration') || 0;

			$target.slideToggle(duration, function () {
				if ($target.is(':visible')) {
					$target.css('display', 'flex');
					$trigger.addClass('elgg-menu-opened')
							.removeClass('elgg-menu-closed')
							.prop('ariaExpanded', true);
					$trigger.parent().addClass('elgg-state-selected');
				} else {
					$trigger.addClass('elgg-menu-closed')
							.removeClass('elgg-menu-opened')
							.prop('ariaExpanded', false);
					$trigger.parent().removeClass('elgg-state-selected');
				}
				
				$trigger.parents('ul.elgg-menu[data-position]').each(function() {
					$(this).position($(this).data().position);
				});
				
			});
		});
		
		// if an anchor also has its own link the text acts as the link, the before pseudo element handles the toggle
		$(document).on('click', '.elgg-menu-item-has-toggle > a > .elgg-anchor-label', function (e) {
			var $anchor = $(this).closest('a');
			var href = $anchor.attr('href');
			
			if ($anchor.hasClass('elgg-non-link') || !href) {
				return;
			}
			
			document.location = href;
			
			e.preventDefault();
			e.stopImmediatePropagation();
		});

		toggle.init = function() {};
	}
};

toggle.init();

export default toggle;
