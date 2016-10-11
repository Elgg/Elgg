/**
 * We use a named AMD module that is inlined in elgg.js, as this module is
 * loaded on each page request and we do not want to issue an additional HTTP request
 *
 * @module elgg/toggle
 * @since 3.0
 */
define('elgg/toggle', ['elgg', 'jquery', 'elgg/animate'], function (elgg, $, animate) {
	
	var toggler = {

		/**
		 * Shortcut to bind a click event on a set of $triggers.
		 *
		 * Set the href to target the item you want to toggle (<a rel="toggle" href="#id-of-target">)
		 * or use data-toggle-selector="your_jquery_selector" to have an advanced selection method
		 *
		 * You can define open and close animations by specifying data-animation attribute on the toggle.
		 *
		 * Plugins can listen to 'elgg_ui_toggle' jQuery event on the trigger to attach additional
		 * behaviour to toggled items after they have been hidden/shown.
		 * 
		 * @param {jQuery} $triggers A set of triggers to bind
		 * @return void
		 */
		bind: function ($triggers) {
			$triggers.off('click.toggle')
					.on('click.toggle', function (e) {
						if (e.isDefaultPrevented()) {
							return;
						}
						e.preventDefault();
						e.stopPropagation();
						toggler.toggle($(this));
					});
		},

		/**
		 * Toggles an element bound to a single $trigger
		 *
		 * @param {jQuery} $trigger A trigger element
		 * @return void
		 */
		toggle: function ($trigger) {
			var href = $trigger.prop('href'),
				selector = $trigger.data('toggleSelector');

			if (!selector) {
				selector = elgg.getSelectorFromUrlFragment(href);
			}

			var animation = $trigger.data('animation') || {};
			
			var $elements = $(selector);

			if ($trigger.is('.elgg-state-active')) {
				$trigger.removeClass('elgg-state-active');
				if (animation.close) {
					animate($elements, animation.close, function($elements) {
						$elements.addClass('hidden').removeClass('elgg-state-toggled');
					});
				} else {
					$elements.addClass('hidden').removeClass('elgg-state-toggled');
				}
			} else {
				$trigger.addClass('elgg-state-active');
				$elements.removeClass('hidden').addClass('elgg-state-toggled');
				if (animation.open) {
					animate($elements, animation.open);
				}
			}
			
			$trigger.trigger('elgg_ui_toggle', [{
				$toggled_elements: $elements
			}]);
		}
	};

	return toggler;
});
