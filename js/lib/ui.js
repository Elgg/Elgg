elgg.provide('elgg.ui');

elgg.ui.init = function () {

	elgg.ui.initHoverMenu();

	//if the user clicks a system message, make it disappear
	$('.elgg-system-messages li').live('click', function() {
		$(this).stop().fadeOut('fast');
	});

	$('.elgg-system-messages li').animate({opacity: 0.9}, 6000);
	$('.elgg-system-messages li').fadeOut('slow');

	$('.elgg-toggle').live('click', elgg.ui.toggle);
	$('.elgg-toggler').live('click', elgg.ui.toggles);

	$('.elgg-menu-page .elgg-menu-parent').live('click', elgg.ui.toggleMenu);

	$('.elgg-like-toggle').live('click', elgg.ui.toggleLikes);

	$('.elgg-requires-confirmation').live('click', elgg.ui.requiresConfirmation);
}

/**
 * Toggles an element based on clicking a separate element
 *
 * Use .elgg-toggle on the toggler element
 * The id of the toggler is elgg-toggler-<id>
 * The id of the element being toggled is elgg-togglee-<id>
 *
 * @param {Object} event
 * @return void
 */
elgg.ui.toggle = function(event) {
	event.preventDefault();

	var id = $(this).toggleClass('elgg-state-active').attr('id').replace('toggler', 'togglee');

	$('#' + id).slideToggle('medium');
}

elgg.ui.toggles = function(event) {
	event.preventDefault();

	$(this).toggleClass('elgg-state-active');

	var togglees = $(this).attr('class').match(/elgg-toggles-[^ ]*/i);

	$('#' + togglees[0].replace('elgg-toggles-', '')).slideToggle('medium');
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

		var $hovermenu = $(this).parent().find(".elgg-menu-hover");

		// close hovermenu if arrow is clicked & menu already open
		if ($hovermenu.css('display') == "block") {
			$hovermenu.fadeOut();
		} else {
			$avatar = $(this).closest(".elgg-avatar");
			$hovermenu.css("top", ($avatar.height()) + "px")
					.css("left", ($avatar.width()-15) + "px")
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