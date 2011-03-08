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

	$('[rel=popup]').live('click', elgg.ui.popsUp);

	$('.elgg-menu-page .elgg-menu-parent').live('click', elgg.ui.toggleMenu);

	$('.elgg-requires-confirmation').live('click', elgg.ui.requiresConfirmation);
	
	$('.elgg-input-date').datepicker();
}

/**
 * Toggles an element based on clicking a separate element
 *
 * Use .elgg-toggler on the toggler element
 * Set the href to target the item you want to toggle (<a class="elgg-toggler" href="#id-of-target">)
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
 * Pops up an element based on clicking a separate element
 *
 * Set the rel="popup" on the popper and set the href to target the
 * item you want to toggle (<a rel="popup" href="#id-of-target">)
 *
 * You can set the position of the popup by putting a certain class on the popper.  Use
 * elgg-popup-<targetH><targetV>-at-<thisH><thisV> where each section is one of the short hands
 * below:
 *	Horizontal:
 *		l: left
 *		c: center
 *		r: right
 *
 *	Vertical:
 *		t: top
 *		c: center
 *		b: bottom
 *
 *	Example:
 *		elgg-popup-lt-at-rb Puts the popup window's left top corner at the popper's right bottom
 *		corner.
 *
 *	You can set the position of the X and Y offsets by putting the class elgg-popup-offset-XXxYY
 *	on the popper where XX and YY are the offsets:
 *		elgg-popup-offset-15x35
 *		
 *	Offsets can be negative:
 *		elgg-popup-offset--5x-35
 *
 * @param {Object} event
 * @return void
 */
elgg.ui.popsUp = function(event) {
	event.preventDefault();

	var target = $(this).toggleClass('elgg-state-active').attr('href');
	var $target = $(target);

	// hide if already open
	if ($target.is(':visible')) {
		$target.fadeOut();
		return;
	}
	
	var posMap = {
		l: 'left',
		c: 'center',
		r: 'right',
		t: 'top',
		b: 'bottom'
	};

	var my = 'left top';
	var at = 'right bottom';
	var offsetX = 0;
	var offsetY = 0;

	// check for location classes on the popper upper
	var posRegexp = new RegExp('elgg-popup-([lcr])([tcb])-at-([lcr])([tcb])$', 'i');
	var offsetRegexp = new RegExp('elgg-popup-offset-(-?[0-9]+x-?[0-9]+)$', 'i');
	var classes = $(this).attr('class').split(' ');
	$(classes).each(function (i, el) {
		if (posRegexp.test(el)) {
			var pos = el.replace(posRegexp, '$1$2$3$4');

			var myH = pos.substr(0, 1);
			var myV = pos.substr(1, 1);
			var toH = pos.substr(2, 1);
			var toV = pos.substr(3, 1);

			my = posMap[myH] + ' ' + posMap[myV];
			at = posMap[toH] + ' ' + posMap[toV];
		} else if (offsetRegexp.test(el)) {
			var offsets = el.replace(offsetRegexp, '$1').split('x');
			offsetX = offsets[0];
			offsetY = offsets[1];
		}
	});

	$target.appendTo('body')
		.fadeIn()
		.css('position', 'absolute')
		.position({
			'my': my,
			'at': at,
			'of': $(this),
			'offset': offsetX + ' ' + offsetY
		});
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