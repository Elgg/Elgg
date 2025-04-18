import 'jquery';
import elgg from 'elgg';
import hooks from 'elgg/hooks';
import 'jquery-ui';
import * as focusTrap from 'focus-trap';

let menuTrap;

var popup = {
	
	/**
	 * Deprecated function
	 *
	 * @return void
	 * @deprecated Since v6.2 this function is no longer in use
	 */
	init: function () {},
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
	 *
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
	 * Handles can also return false to abort the default behavior and override it with their own.
	 *
	 * @param {jQuery} $trigger Trigger element
	 * @param {jQuery} $target  Target popup module
	 * @param {object} position Positioning data of the $target module
	 *
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

		position = hooks.trigger('getOptions', 'ui.popup', params, position);

		if (!position) {
			return;
		}
		
		// cleanup trigger tracking
		$('[data-popup-trigger-closed]').removeAttr('data-popup-trigger-closed');
		
		popup.close(); // close any open popup modules

		$target.data('trigger', $trigger); // used to remove elgg-state-active class when popup is closed
		$target.data('position', position); // used to reposition the popup module on window manipulations

		if (!$trigger.is('.elgg-popup-inline')) { // @todo remove this class check in Elgg 7.0
			$target.appendTo('body');
		}
		
		// need to do a double position because of positioning issues during fadeIn() in Opera
		// https://github.com/Elgg/Elgg/issues/6452
		$target.position(position).fadeIn()
			   .addClass('elgg-state-active elgg-state-popped')
			   .position(position);

		$trigger.addClass('elgg-state-active');
		
		$target.trigger('open');
		
		menuTrap = focusTrap.createFocusTrap(targetSelector, {
			returnFocusOnDeactivate: false,
			initialFocus: function() {
				if ($target.find(':focus').length || $target.is(':focus')) {
					return false;
				}
			},
			clickOutsideDeactivates: function(e) {
				if ($trigger.is($(e.target)) || ($trigger.find($(e.target)).length)) {
					$trigger.attr('data-popup-trigger-closed', true);
				}
				
				// prevent deactivation for outside clicks on autocomplete results
				return $(e.target).parents('.ui-autocomplete, .ui-datepicker').length === 0;
			},
			allowOutsideClick: true,
			onDeactivate: function() {
				popup.close(undefined,false);
			}
		});
		menuTrap.activate();
	},
	/**
	 * Hides a set of $targets. If not defined, closes all visible popup modules.
	 *
	 * @param {jQuery}  $targets           Popup modules to hide
	 * @param {boolean} deactive_menu_trap should we try to deactivate the menu trap first
	 *
	 * @return void
	 */
	close: function ($targets, deactive_menu_trap = true) {
		if (deactive_menu_trap && (typeof menuTrap !== 'undefined')) {
			menuTrap.deactivate();
			return;
		}
		
		if (typeof $targets === 'undefined') {
			$targets = $('.elgg-state-popped');
		}
		
		if (!$targets.length) {
			return;
		}
		
		$targets.each(function () {
			const $target = $(this);
			if (!$target.is(':visible')) {
				return;
			}
			
			const $trigger = $target.data('trigger');
			if ($trigger.length) {
				$trigger.removeClass('elgg-state-active');
				$trigger.trigger('focus');
			}
			
			$target.fadeOut().removeClass('elgg-state-active elgg-state-popped');
			
			$target.trigger('close');
		});
	}
};

popup.bind($('.elgg-popup'));

export default popup;
