elgg.provide('elgg.ui');

elgg.ui.init = function () {
	// @todo we need better documentation for this hack
	// iOS Hover Event Class Fix
	$('.elgg-page').attr("onclick", "return true");

	// add user hover menus
	elgg.ui.initHoverMenu();

	//if the user clicks a system message, make it disappear
	$('.elgg-system-messages li').live('click', function() {
		$(this).stop().fadeOut('fast');
	});

	$('.elgg-system-messages li').animate({opacity: 0.9}, 6000);
	$('.elgg-system-messages li.elgg-state-success').fadeOut('slow');

	$('[rel=toggle]').live('click', elgg.ui.toggles);

	$('[rel=popup]').live('click', elgg.ui.popupOpen);

	$('.elgg-menu-page .elgg-menu-parent').live('click', elgg.ui.toggleMenu);

    $('*[data-confirm], .elgg-requires-confirmation').live('click', elgg.ui.requiresConfirmation);
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
		target = $this.data().toggleSelector;
	
	if (!target) {
		// @todo we can switch to elgg.getSelectorFromUrlFragment() in 1.x if
		// we also extend it to support href=".some-class"
		target = $this.attr('href');
	}

	$this.toggleClass('elgg-state-active');

	$(target).each(function(index, elem) {
		var $elem = $(elem);
		if ($elem.data().toggleSlide != false) {
			$elem.slideToggle('medium');
		} else {
			$elem.toggle();
		}
	});
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
	event.preventDefault();
	event.stopPropagation();

	var target = elgg.getSelectorFromUrlFragment($(this).toggleClass('elgg-state-active').attr('href'));
	var $target = $(target);

	// emit a hook to allow plugins to position and control popups
	var params = {
		targetSelector: target,
		target: $target,
		source: $(this)
	};

	var options = {
		my: 'center top',
		at: 'center bottom',
		of: $(this),
		collision: 'fit fit'
	};

	options = elgg.trigger_hook('getOptions', 'ui.popup', params, options);

	// allow plugins to cancel event
	if (!options) {
		return;
	}

	// hide if already open
	if ($target.is(':visible')) {
		$target.fadeOut();
		$('body').die('click', elgg.ui.popupClose);
		return;
	}

	$target.appendTo('body')
		.fadeIn()
		.position(options);

	$('body')
		.die('click', elgg.ui.popupClose)
		.live('click', elgg.ui.popupClose);
};

/**
 * Catches clicks that aren't in a popup and closes all popups.
 */
elgg.ui.popupClose = function(event) {
	$eventTarget = $(event.target);
	var inTarget = false;
	var $popups = $('[rel=popup]');

	// if the click event target isn't in a popup target, fade all of them out.
	$popups.each(function(i, e) {
		var target = elgg.getSelectorFromUrlFragment($(e).attr('href')) + ':visible';
		var $target = $(target);

		if (!$target.is(':visible')) {
			return;
		}

		// didn't click inside the target
		if ($eventTarget.closest(target).length > 0) {
			inTarget = true;
			return false;
		}
	});

	if (!inTarget) {
		$popups.each(function(i, e) {
			var $e = $(e);
			var $target = $(elgg.getSelectorFromUrlFragment($e.attr('href')) + ':visible');
			if ($target.length > 0) {
				$target.fadeOut();
				$e.removeClass('elgg-state-active');
			}
		});

		$('body').die('click', elgg.ui.popClose);
	}
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
	$(parent).find(".elgg-avatar").live('mouseover', function() {
		$(this).children(".elgg-icon-hover-menu").show();
	})
	.live('mouseout', function() {
		$(this).children(".elgg-icon-hover-menu").hide();
	});


	// avatar contextual menu
	$(".elgg-avatar > .elgg-icon-hover-menu").live('click', function(e) {
		var $placeholder = $(this).parent().find(".elgg-menu-hover.elgg-ajax-loader");

		if ($placeholder.length) {
			loadMenu($placeholder.attr("rel"));
		}

		// check if we've attached the menu to this element already
		var $hovermenu = $(this).data('hovermenu') || null;

		if (!$hovermenu) {
			$hovermenu = $(this).parent().find(".elgg-menu-hover");
			$(this).data('hovermenu', $hovermenu);
		}

		// close hovermenu if arrow is clicked & menu already open
		if ($hovermenu.css('display') == "block") {
			$hovermenu.fadeOut();
		} else {
			$avatar = $(this).closest(".elgg-avatar");

			// @todo Use jQuery-ui position library instead -- much simpler
			var offset = $avatar.offset();
			var top = $avatar.height() + offset.top + 'px';
			var left = $avatar.width() - 15 + offset.left + 'px';

			$hovermenu.appendTo('body')
					.css('position', 'absolute')
					.css("top", top)
					.css("left", left)
					.fadeIn('normal');
		}

		// hide any other open hover menus
		$(".elgg-menu-hover:visible").not($hovermenu).fadeOut();
	});

	// hide avatar menu when user clicks elsewhere
	$(document).click(function(event) {
		if ($(event.target).parents(".elgg-avatar").length === 0) {
			$(".elgg-menu-hover").fadeOut();
		}
	});
};

/**
 * Calls a confirm() and prevents default if denied.
 *
 * @param {Object} e
 * @return void
 */
elgg.ui.requiresConfirmation = function(e) {
	var confirmText = $(this).data('confirm') || elgg.echo('question:areyousure');
	if (!confirm(confirmText)) {
		e.preventDefault();
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
elgg.ui.initDatePicker = function() {
	function loadDatePicker() {
		$('.elgg-input-date').datepicker({
			// ISO-8601
			dateFormat: 'yy-mm-dd',
			onSelect: function(dateText) {
				if ($(this).is('.elgg-input-timestamp')) {
					// convert to unix timestamp
					var dateParts = dateText.split("-");
					var timestamp = Date.UTC(dateParts[0], dateParts[1] - 1, dateParts[2]);
					timestamp = timestamp / 1000;

					var id = $(this).attr('id');
					$('input[name="' + id + '"]').val(timestamp);
				}
			},
			nextText: '&#xBB;',
			prevText: '&#xAB;',
			changeMonth: true,
			changeYear: true
		});
	}

	if (!$('.elgg-input-date').length) {
		return;
	}

	if (elgg.get_language() == 'en') {
		loadDatePicker();
	} else {
		// load language first
		elgg.get({
			url: elgg.config.wwwroot + 'vendors/jquery/i18n/jquery.ui.datepicker-'+ elgg.get_language() +'.js',
			dataType: "script",
			cache: true,
			success: loadDatePicker,
			error: loadDatePicker // english language is already loaded.
		});
	}
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
	$('.elgg-menu-item-' + menuItemNameA + ' a').live('click', function() {
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
	$('.elgg-menu-item-' + menuItemNameB + ' a').live('click', function() {
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
elgg.register_hook_handler('init', 'system', elgg.ui.initDatePicker);
elgg.register_hook_handler('getOptions', 'ui.popup', elgg.ui.loginHandler);
elgg.ui.registerTogglableMenuItems('add-friend', 'remove-friend');
