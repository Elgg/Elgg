elgg.provide('elgg.ui');

elgg.ui.init = function () {
	//if the user clicks a system message, make it disappear
	$('.elgg-system-messages li').live('click', function() {
		$(this).stop().fadeOut('fast');
	});

	$('.elgg-toggle').live('click', elgg.ui.toggle);

	$('.elgg-menu-parent').live('click', elgg.ui.toggleMenu);

	$('.elgg-like-toggle').live('click', elgg.ui.toggleLikes);
	
	$('a.collapsibleboxlink').click(elgg.ui.toggleCollapsibleBox);
};

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
	var id = $(this).attr('id').replace('toggler', 'togglee');
	$('#' + id).slideToggle('medium');
	event.preventDefault();
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

// reusable generic hidden panel
elgg.ui.toggleCollapsibleBox = function () {
	//$(this.parentNode.parentNode).children(".collapsible_box").slideToggle("fast");
	return false;
};

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

elgg.register_event_handler('init', 'system', elgg.ui.init);