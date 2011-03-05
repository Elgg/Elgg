elgg.provide('elgg.ui');

elgg.ui.init = function () {

	elgg.ui.initHoverMenu();

	//if the user clicks a system message, make it disappear
	$('.elgg-system-messages li').live('click', function() {
		$(this).stop().fadeOut('fast');
	});

	$('.elgg-system-messages li').animate({opacity: 0.9}, 6000);
	$('.elgg-system-messages li').fadeOut('slow');

	$('.elgg-toggler').live('click', elgg.ui.toggles);

	$('.elgg-menu-page .elgg-menu-parent').live('click', elgg.ui.toggleMenu);

	$('.elgg-like-toggle').live('click', elgg.ui.toggleLikes);

	$('.elgg-requires-confirmation').live('click', elgg.ui.requiresConfirmation);
	
	$('.elgg-input-date').datepicker();
}

/**
 * Toggles an element based on clicking a separate element
 *
 * Use .elgg-toggler on the toggler element
 * Set the href to target the item you want to toggle (<a href="#id-of-target">)
 *
 * @param {Object} event
 * @return void
 */
elgg.ui.toggles = function(event) {
	event.preventDefault();

	var target = $(this).toggleClass('elgg-state-active').attr('href');

	$(target).slideToggle('medium');
}

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
}

/**
 * Toggles the likes list
 *
 * @param {Object} event
 * @return void
 */
elgg.ui.toggleLikes = function(event) {
	var $list = $(this).next(".elgg-likes-list");
	var position = $(this).position();
	var startTop = position.top;
	var stopTop = position.top - $list.height();
	if ($list.css('display') == 'none') {
		$('.elgg-likes-list').fadeOut();

		$list.css('top', startTop);
		$list.css('left', position.left - $list.width());
		$list.animate({opacity: "toggle", top: stopTop}, 500);

		$list.click(function(event) {
			$list.fadeOut();
		});
	} else {
		$list.animate({opacity: "toggle", top: startTop}, 500);
	}
	event.preventDefault();
}

/**
 * Initialize the hover menu
 *
 * @param {Object} parent
 * @return void
 */
elgg.ui.initHoverMenu = function(parent) {
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
		// check if we've attached the menu to this element already
		var $hovermenu = $(this).data('hovermenu') || null;

		if (!$hovermenu) {
			var $hovermenu = $(this).parent().find(".elgg-menu-hover");
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
		if ($(event.target).parents(".elgg-avatar").length == 0) {
			$(".elgg-menu-hover").fadeOut();
		}
	});
}

/**
 * Calls a confirm() and prevents default if denied.
 *
 * @param {Object} event
 * @return void
 */
elgg.ui.requiresConfirmation = function(e) {
	var confirmText = $(this).attr('title') || elgg.echo('question:areyousure');
	if (!confirm(confirmText)) {
		e.preventDefault();
	}
};

elgg.register_event_handler('init', 'system', elgg.ui.init);