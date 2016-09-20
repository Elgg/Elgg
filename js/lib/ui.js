elgg.provide('elgg.ui');

elgg.ui.init = function () {
	// @todo we need better documentation for this hack
	// iOS Hover Event Class Fix
	$('.elgg-page').attr("onclick", "return true");

	// add user hover menus
	elgg.ui.initHoverMenu();

	// if the user clicks a system message (not a link inside one), make it disappear
	$(document).on('click', '.elgg-system-messages li', function(e) {
		if (!$(e.target).is('a')) {
			$(this).stop().fadeOut('fast');
		}
	});

	$('.elgg-system-messages li').animate({opacity: 0.9}, 6000);
	$('.elgg-system-messages li.elgg-state-success').fadeOut('slow');

	$(document).on('click', '[rel=toggle]', elgg.ui.toggles);

	require(['elgg/popup'], function(popup) {
		popup.bind($('[rel="popup"]'));
	});

	$(document).on('click', '.elgg-menu-page .elgg-menu-parent', elgg.ui.toggleMenu);

    $(document).on('click', '*[data-confirm], .elgg-requires-confirmation', elgg.ui.requiresConfirmation);
    if ($('.elgg-requires-confirmation').length > 0) {
        elgg.deprecated_notice('Use of .elgg-requires-confirmation is deprecated by data-confirm', '1.10');
    }

	$('.elgg-autofocus').focus();
	if ($('.elgg-autofocus').length > 0) {
		elgg.deprecated_notice('Use of .elgg-autofocus is deprecated by html5 autofocus', 1.9);
	}

	elgg.ui.initAccessInputs();

	// Allow element to be highlighted using CSS if its id is found from the URL
	var elementId = elgg.getSelectorFromUrlFragment(document.URL);
	$(elementId).addClass('elgg-state-highlight');

	elgg.ui.initDatePicker();

	elgg.ui.registerTogglableMenuItems('add-friend', 'remove-friend');
};

/**
 * Toggles an element based on clicking a separate element
 *
 * Use rel="toggle" on the toggler element
 * Set the href to target the item you want to toggle (<a rel="toggle" href="#id-of-target">)
 * or use data-toggle-selector="your_jquery_selector" to have an advanced selection method
 *
 * By default elements perform a slideToggle.
 * If you want a normal toggle (hide/show) you can add data-toggle-slide="0" on the elements to prevent a slide.
 *
 * @param {Object} event
 * @return void
 */
elgg.ui.toggles = function(event) {
	event.preventDefault();
	var $this = $(this),
		selector = $this.data().toggleSelector;

	if (!selector) {
		// @todo we can switch to elgg.getSelectorFromUrlFragment() in 1.x if
		// we also extend it to support href=".some-class"
		selector = $this.attr('href');
	}

	var $elements = $(selector);

	$this.toggleClass('elgg-state-active');

	$elements.each(function(index, elem) {
		var $elem = $(elem);
		if ($elem.data().toggleSlide != false) {
			$elem.slideToggle('medium');
		} else {
			$elem.toggle();
		}
	});

	$this.trigger('elgg_ui_toggle', [{
		$toggled_elements: $elements
	}]);
};

/**
 * Pops up an element based on clicking a separate element
 *
 * Set the rel="popup" on the popper and set the href to target the
 * item you want to toggle (<a rel="popup" href="#id-of-target">)
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
 * @param {Object} event
 * @return void
 */
elgg.ui.popupOpen = function(event) {

	elgg.deprecated_notice('elgg.ui.popupOpen() has been deprecated and should not be called directly. Use elgg/popup AMD module instead', '2.2');

	event.preventDefault();
	event.stopPropagation();

	var $elem = $(this);
	require(['elgg/popup'], function(popup) {
		popup.open($elem);
	});
};

/**
 * Catches clicks that aren't in a popup and closes all popups.
 * @deprecated 2.2
 */
elgg.ui.popupClose = function(event) {
	
	elgg.deprecated_notice('elgg.ui.popupClose() has been deprecated and should not be called directly. Use elgg/popup AMD module instead', '2.2');

	require(['elgg/popup'], function(popup) {
		popup.close();
	});
};

/**
 * Toggles a child menu when the parent is clicked
 *
 * @param {Object} event
 * @return void
 */
elgg.ui.toggleMenu = function(event) {
	$(this).siblings().slideToggle('medium');
	$(this).toggleClass('elgg-menu-closed elgg-menu-opened');
	event.preventDefault();
};

/**
 * Initialize the hover menu
 *
 * @param {Object} parent
 * @return void
 */
elgg.ui.initHoverMenu = function(parent) {

	/**
	 * For a menu clicked, load the menu into all matching placeholders
	 *
	 * @param {String} mac Machine authorization code for the menu clicked
	 */
	function loadMenu(mac) {
		var $all_placeholders = $(".elgg-menu-hover[rel='" + mac + "']");

		// find the <ul> that contains data for this menu
		var $ul = $all_placeholders.filter('[data-elgg-menu-data]');

		if (!$ul.length) {
			return;
		}

		elgg.get('ajax/view/navigation/menu/user_hover/contents', {
			data: $ul.data('elggMenuData'),
			success: function(data) {
				if (data) {
					// replace all existing placeholders with new menu
					$all_placeholders.removeClass('elgg-ajax-loader')
						.html($(data).children());
				}
			}
		});
	}

	if (!parent) {
		parent = document;
	}

	// avatar image menu link
	$(parent).on('mouseover', ".elgg-avatar", function() {
		$(this).children(".elgg-icon-hover-menu").show();
	})
	.on('mouseout', '.elgg-avatar', function() {
		$(this).children(".elgg-icon-hover-menu").hide();
	});


	// avatar contextual menu
	$(document).on('click', ".elgg-avatar > .elgg-icon-hover-menu", function(e) {

		var $icon = $(this);

		var $placeholder = $icon.parent().find(".elgg-menu-hover.elgg-ajax-loader");

		if ($placeholder.length) {
			loadMenu($placeholder.attr("rel"));
		}

		// check if we've attached the menu to this element already
		var $hovermenu = $icon.data('hovermenu') || null;

		if (!$hovermenu) {
			$hovermenu = $icon.parent().find(".elgg-menu-hover");
			$icon.data('hovermenu', $hovermenu);
		}

		require(['elgg/popup'], function(popup) {
			if ($hovermenu.is(':visible')) {
				// close hovermenu if arrow is clicked & menu already open
				popup.close($hovermenu);
			} else {
				popup.open($icon, $hovermenu, {
					'my': 'left top',
					'at': 'right-15px bottom',
					'of': $icon.closest(".elgg-avatar"),
					'collision': 'fit fit'
				});
			}
		});
	});

};

/**
 * Calls a confirm() and returns false if denied.
 *
 * @param {Object} e
 * @return void
 */
elgg.ui.requiresConfirmation = function(e) {
	var confirmText = $(this).data('confirm') || elgg.echo('question:areyousure');
	if (!confirm(confirmText)) {
		return false;
	}
};

/**
 * Repositions the login popup
 *
 * @param {String} hook    'getOptions'
 * @param {String} type    'ui.popup'
 * @param {Object} params  An array of info about the target and source.
 * @param {Object} options Options to pass to
 *
 * @return {Object}
 */
elgg.ui.loginHandler = function(hook, type, params, options) {
	if (params.target.attr('id') == 'login-dropdown-box') {
		options.my = 'right top';
		options.at = 'right bottom';
		return options;
	}
	return null;
};

/**
 * Initialize the date picker
 *
 * Uses the class .elgg-input-date as the selector.
 *
 * If the class .elgg-input-timestamp is set on the input element, the onSelect
 * method converts the date text to a unix timestamp in seconds. That value is
 * stored in a hidden element indicated by the id on the input field.
 *
 * @return void
 * @requires jqueryui.datepicker
 */
elgg.ui.initDatePicker = function () {
	var selector = '.elgg-input-date:not([data-datepicker-opts])';
	if (!$(selector).length) {
		return;
	}
	elgg.deprecated_notice('elgg.ui.initDatePicker() has been deprecated. Use input/date AMD module instead', '2.1');
	require(['input/date'], function (datepicker) {
		datepicker.init(selector);
	});
};

/**
 * This function registers two menu items that are actions that are the opposite
 * of each other and ajaxifies them. E.g. like/unlike, friend/unfriend, ban/unban, etc.
 *
 * Note the menu item names must be given in their normalized form. So if the
 * name is remove_friend, you should call this function with "remove-friend" instead.
 */
elgg.ui.registerTogglableMenuItems = function(menuItemNameA, menuItemNameB) {

	// Handles clicking the first button.
	$(document).off('click.togglable', '.elgg-menu-item-' + menuItemNameA + ' a')
			.on('click.togglable', '.elgg-menu-item-' + menuItemNameA + ' a', function() {
		var $menu = $(this).closest('.elgg-menu');

		// Be optimistic about success
		elgg.ui.toggleMenuItems($menu, menuItemNameB, menuItemNameA);

		// Send the ajax request
		elgg.action($(this).attr('href'), {
			success: function(json) {
				if (json.system_messages.error.length) {
					// Something went wrong, so undo the optimistic changes
					elgg.ui.toggleMenuItems($menu, menuItemNameA, menuItemNameB);
				}
			},
			error: function() {
				// Something went wrong, so undo the optimistic changes
				elgg.ui.toggleMenuItems($menu, menuItemNameA, menuItemNameB);
			}
		});

		// Don't want to actually click the link
		return false;
	});

	// Handles clicking the second button
	$(document).off('click.togglable', '.elgg-menu-item-' + menuItemNameB + ' a')
			.on('click.togglable', '.elgg-menu-item-' + menuItemNameB + ' a', function() {
		var $menu = $(this).closest('.elgg-menu');

		// Be optimistic about success
		elgg.ui.toggleMenuItems($menu, menuItemNameA, menuItemNameB);

		// Send the ajax request
		elgg.action($(this).attr('href'), {
			success: function(json) {
				if (json.system_messages.error.length) {
					// Something went wrong, so undo the optimistic changes
					elgg.ui.toggleMenuItems($menu, menuItemNameB, menuItemNameA);
				}
			},
			error: function() {
				// Something went wrong, so undo the optimistic changes
				elgg.ui.toggleMenuItems($menu, menuItemNameB, menuItemNameA);
			}
		});

		// Don't want to actually click the link
		return false;
	});
};

elgg.ui.toggleMenuItems = function($menu, nameOfItemToShow, nameOfItemToHide) {
    $menu.find('.elgg-menu-item-' + nameOfItemToShow).removeClass('hidden').find('a').focus();
    $menu.find('.elgg-menu-item-' + nameOfItemToHide).addClass('hidden');
};

/**
 * Initialize input/access for dynamic display of members only warning
 *
 * If a select.elgg-input-access is accompanied by a note (.elgg-input-access-membersonly),
 * then hide the note when the select value is PRIVATE or group members.
 *
 * @return void
 * @since 1.9.0
 */
elgg.ui.initAccessInputs = function () {
	$('.elgg-input-access').each(function () {
		function updateMembersonlyNote() {
			var val = $select.val();
			if (val != acl && val !== 0) {
				// .show() failed in Chrome. Maybe a float/jQuery bug
				$note.css('visibility', 'visible');
			} else {
				$note.css('visibility', 'hidden');
			}
		}
		var $select = $(this),
			acl = $select.data('group-acl'),
			$note = $('.elgg-input-access-membersonly', this.parentNode),
			commentCount = $select.data('comment-count'),
			originalValue = $select.data('original-value');
		if ($note) {
			updateMembersonlyNote();
			$select.change(updateMembersonlyNote);
		}

		if (commentCount) {
			$select.change(function(e) {
				if ($(this).val() != originalValue) {
					if (!confirm(elgg.echo('access:comments:change', [commentCount]))) {
						$(this).val(originalValue);
					}
				}
			});
		}
	});
};

elgg.register_hook_handler('init', 'system', elgg.ui.init);
elgg.register_hook_handler('getOptions', 'ui.popup', elgg.ui.loginHandler);
