/**
 * We use a named AMD module that is inlined in elgg.js, as this module is
 * loaded on each page request and we do not want to issue an additional HTTP request
 *
 * @module elgg/popup
 * @since 2.2
 */
define('elgg/popup', ['elgg', 'jquery', 'jquery-ui/position', 'jquery-ui/unique-id'], function (elgg, $) {

	var popup = {
		/**
		 * Initializes a popup module and binds an event to hide visible popup
		 * modules on a click event outside of their DOM stack.
		 *
		 * This method is called before the popup module is displayed.
		 *
		 * @return void
		 */
		init: function () {
			$(document).on('click', function (e) {
				if (e.isDefaultPrevented()) {
					return;
				}
				var $eventTargets = $(e.target).parents().addBack();
				if ($eventTargets.is('.elgg-state-popped')) {
					return;
				}
				popup.close();
			});
			// Bind events only once
			popup.init = elgg.nullFunction;
		},
		/**
		 * Shortcut to bind a click event on a set of $triggers.
		 *
		 * Set the '[rel="popup"]' of the $trigger and set the href to target the
		 * item you want to toggle (<a rel="popup" href="#id-of-target">).
		 *
		 * This method is called by core JS UI library for all [rel="popup"] elements,
		 * but can be called by plugins to bind other triggers.
		 *
		 * @param {jQuery} $triggers A set of triggers to bind
		 * @return void
		 */
		bind: function ($triggers) {
			$triggers.off('click.popup')
					.on('click.popup', function (e) {
						if (e.isDefaultPrevented()) {
							return;
						}
						e.preventDefault();
						e.stopPropagation();
						popup.open($(this));
					});
		},
		/**
		 * Shows a $target element at a given position
		 * If no $target element is provided, determines it by parsing hash from href attribute of the $trigger
		 *
		 * This function emits the getOptions, ui.popup hook that plugins can register for to provide custom
		 * positioning for elements.  The handler is passed the following params:
		 *	targetSelector: The selector used to find the popup
		 *	target:         The popup jQuery element as found by the selector
		 *	source:         The jquery element whose click event initiated a popup.
		 *
		 * The return value of the function is used as the options object to .position().
		 * Handles can also return false to abort the default behvior and override it with their own.
		 *
		 * @param {jQuery} $trigger Trigger element
		 * @param {jQuery} $target  Target popup module
		 * @param {object} position Positioning data of the $target module
		 * @return void
		 */
		open: function ($trigger, $target, position) {
			if (typeof $trigger === 'undefined' || !$trigger.length) {
				return;
			}

			if (typeof $target === 'undefined') {
				var href = $trigger.attr('href') || $trigger.data('href') || '';
				var targetSelector = elgg.getSelectorFromUrlFragment(href);
				$target = $(targetSelector);
			} else {
				$target.uniqueId();
				var targetSelector = '#' + $target.attr('id');
			}

			if (!$target.length) {
				return;
			}

			// emit a hook to allow plugins to position and control popups
			var params = {
				targetSelector: targetSelector,
				target: $target,
				source: $trigger
			};

			position = position || {
				my: 'center top',
				at: 'center bottom',
				of: $trigger,
				collision: 'fit fit'
			};

			$.extend(position, $trigger.data('position'));

			position = elgg.trigger_hook('getOptions', 'ui.popup', params, position);

			if (!position) {
				return;
			}

			popup.init();

			// If the user is clicking on the trigger while the popup is open
			// we should just close the popup
			if ($target.is('.elgg-state-popped')) {
				popup.close($target);
				return;
			}
			
			popup.close(); // close any open popup modules

			$target.data('trigger', $trigger); // used to remove elgg-state-active class when popup is closed
			$target.data('position', position); // used to reposition the popup module on window manipulations

			if (!$trigger.is('.elgg-popup-inline')) {
				$target.appendTo('body');

				// when a popup is absolutely positioned, close the popup on scroll
				$(document).one('scroll', function() {
					popup.close();
				});
			}
			
			// need to do a double position because of positioning issues during fadeIn() in Opera
			// https://github.com/Elgg/Elgg/issues/6452
			$target.position(position).fadeIn()
				   .addClass('elgg-state-active elgg-state-popped')
				   .position(position);

			$trigger.addClass('elgg-state-active');

			$target.trigger('open');
		},
		/**
		 * Hides a set of $targets. If not defined, closes all visible popup modules.
		 *
		 * @param {jQuery} $targets Popup modules to hide
		 * @return void
		 */
		close: function ($targets) {
			if (typeof $targets === 'undefined') {
				$targets = $('.elgg-state-popped');
			}
			if (!$targets.length) {
				return;
			}
			$targets.each(function () {
				var $target = $(this);
				if (!$target.is(':visible')) {
					return;
				}

				var $trigger = $target.data('trigger');
				if ($trigger.length) {
					$trigger.removeClass('elgg-state-active');
				}

				$target.fadeOut().removeClass('elgg-state-active elgg-state-popped');

				$target.trigger('close');
			});
		}
	};

	return popup;
});
