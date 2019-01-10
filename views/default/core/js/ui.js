elgg.provide('elgg.ui');

elgg.ui.init = function () {
	// @todo we need better documentation for this hack
	// iOS Hover Event Class Fix
	$('.elgg-page').attr("onclick", "return true");

	// add user hover menus
	elgg.ui.initHoverMenu();

	// if the user clicks a system message (not a link inside one), make it disappear
	$(document).on('click', '.elgg-system-messages .elgg-message', function(e) {
		if (!$(e.target).is('a')) {
			var $this = $(this);

			// slideUp allows dismissals without notices shifting around unpredictably
			$this.clearQueue().slideUp(100, function () {
				$this.remove();
			});
		}
	});

	$('.elgg-page-default .elgg-system-messages .elgg-message').parent().animate({opacity: 0.9}, 6000);
	$('.elgg-page-default .elgg-system-messages .elgg-message-success').parent().fadeOut('slow');

	$(document).on('click', '[rel=toggle]', elgg.ui.toggles);

	require(['elgg/popup'], function(popup) {
		popup.bind($('[rel="popup"]'));
	});

	$(document).on('click', '*[data-confirm]', elgg.ui.requiresConfirmation);

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
 * Initialize the hover menu
 *
 * @param {Object} parent
 * @return void
 */
elgg.ui.initHoverMenu = function(parent) {

	/**
	 * For a menu clicked, load the menu into all matching placeholders
	 *
	 * @param {String}   mac      Machine authorization code for the menu clicked
	 * @param {Function} callback a callback function to call when the loading of het menu was succesfull
	 */
	function loadMenu(mac, callback) {
		var $all_placeholders = $(".elgg-menu-hover[rel='" + mac + "']");
		
		// find the <ul> that contains data for this menu
		var $ul = $all_placeholders.filter('[data-elgg-menu-data]');

		if (!$ul.length) {
			return;
		}
		
		require(['elgg/Ajax'], function(Ajax) {
			var ajax = new Ajax();
			ajax.view('navigation/menu/user_hover/contents', {
				data: $ul.data('elggMenuData'),
				success: function(data) {
					if (data) {
						// replace all existing placeholders with new menu
						$all_placeholders.html($(data));
					}
					
					if (typeof callback === 'function') {
						callback();
					}
				},
				complete: function() {
					$all_placeholders.removeAttr('data-menu-placeholder');
				}
			});
		});
	};
	
	/**
	 * Show the hover menu in a popup module
	 *
	 * @params {jQuery} $icon the user icon which was clicked
	 */
	function showPopup($icon) {
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
					'at': 'left top',
					'of': $icon.closest(".elgg-avatar"),
					'collision': 'fit fit'
				});
			}
		});
	};

	if (!parent) {
		parent = document;
	}

	// avatar contextual menu
	$(document).on('click', ".elgg-avatar-menu > a", function(e) {
		e.preventDefault();

		var $icon = $(this);

		var $placeholder = $icon.parent().find(".elgg-menu-hover[data-menu-placeholder]");

		if ($placeholder.length) {
			loadMenu($placeholder.attr("rel"), function() {
				showPopup($icon);
			});
		} else {
			showPopup($icon);
		}
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
		e.preventDefault();
		e.stopPropagation();
		e.stopImmediatePropagation();
		return false;
	}
};

/**
 * This function registers two menu items that are actions that are the opposite
 * of each other and ajaxifies them. E.g. like/unlike, friend/unfriend, ban/unban, etc.
 *
 * You can also add the data parameter 'data-toggle' to menu items to have them automatically
 * registered as toggleable without the need to call this function.
 */
elgg.ui.registerTogglableMenuItems = function(menuItemNameA, menuItemNameB) {
	require(['navigation/menu/elements/item_toggle'], function() {
		menuItemNameA = menuItemNameA.replace('_', '-');
		menuItemNameB = menuItemNameB.replace('_', '-');

		$('.elgg-menu-item-' + menuItemNameA + ' a').not('[data-toggle]').attr('data-toggle', menuItemNameB);
		$('.elgg-menu-item-' + menuItemNameB + ' a').not('[data-toggle]').attr('data-toggle', menuItemNameA);
	});
};

elgg.register_hook_handler('init', 'system', elgg.ui.init);
