define(function (require) {

	var elgg = require('elgg');
	var $ = require('jquery');
	require('jquery-ui');

	var popup = {
		ready: false,
		/**
		 * Initializes a popup module
		 * - Binds an event to hide visible popup modules on a click event outside of their DOM stack
		 * - Binds an event to reposition the popup when window is scrolled or resized
		 *
		 * This method is called before the popup module is displayed
		 * 
		 * @returns {void}
		 */
		init: function () {
			if (popup.ready) {
				return;
			}
			$(document).on('click', function (e) {
				var $eventTargets = $(e.target).parents().andSelf();
				if ($eventTargets.is('[data-popup]')) {
					return;
				}
				popup.close();
			});

			// Adding this magic so that popups with fixed position stick to their parent element
			$(window).on('scroll resize', function () {
				$('[data-popup]:visible').each(function () {
					var position = $(this).data('position');
					if (position) {
						$(this).position(position);
					}
				});
			});
			popup.ready = true;
		},
		/**
		 * Shortcut to bind a click event on a set of $triggers
		 *
		 * Set the 'class="elgg-popup"' of the $trigger and set the href to target the
		 * item you want to toggle (<a class="elgg-popup" href="#id-of-target">).
		 *
		 * This method is called by core JS UI library for all [rel="popup"],.elgg-popup elements,
		 * but can be called by plugins to bind other triggers
		 *
		 * @param {jQuery} $triggers A set of triggers to bind
		 * @returns {void}
		 */
		bind: function ($triggers) {
			$triggers.each(function () {
				$(this).off('click.popup')
						.on('click.popup', function (e) {
							if (e.isDefaultPrevented()) {
								return;
							}
							e.preventDefault();
							e.stopPropagation();
							popup.open($(this));
						});
			});
		},
		/**
		 * Shows a $target element at a given position
		 * If no $target element is provided, determines it by parsing href and data-href attributes of the $trigger
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

			position = position || {
				my: 'center top',
				at: 'center bottom',
				of: $trigger,
				collision: 'fit fit'
			};

			$.extend(position, $trigger.data('position'));

			// emit a hook to allow plugins to position and control popups
			var params = {
				targetSelector: targetSelector,
				target: $target,
				source: $trigger
			};

			position = elgg.trigger_hook('getOptions', 'ui.popup', params, position);

			if (!position) {
				return;
			}

			popup.init();
			popup.close();

			$trigger.addClass('elgg-state-active');

			$target.data('trigger', $trigger); // used to remove elgg-state-active class when popup is closed
			$target.data('position', position); // used to reposition the popup module on window manipulations

			// @todo: in 3.0, do not append to 'body' and use fixed positioning with z-indexes instead
			$target.appendTo('body')
					.fadeIn()
					.addClass('elgg-state-active')
					.attr('data-popup', true)
					.position(position);

			$target.trigger('open');
		},
		/**
		 * Hides a set of $targets. If not defined, closes all visible popup modules
		 *
		 * @param {jQuery} $targets
		 * @returns {undefined}
		 */
		close: function ($targets) {
			if (typeof $targets === 'undefined') {
				$targets = $('[data-popup]');
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

				$target.fadeOut()
						.removeClass('elgg-state-active')
						.removeAttr('data-popup');

				$target.trigger('close');
			});
		}
	};
	return popup;

});