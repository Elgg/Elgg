elgg.provide('elgg.ui');

elgg.ui.init = function () {
	//if the user clicks a system message, make it disappear
	$('.elgg-system-messages li').live('click', function() {
		$(this).stop().fadeOut('fast');
	});

	$('.elgg-toggle').live('click', elgg.ui.toggle);

	$('.elgg-menu-parent').live('click', elgg.ui.menuToggle);

	
	$('a.collapsibleboxlink').click(elgg.ui.toggleCollapsibleBox);

	// set-up hover class for dragged widgets
	var cols = [
		"#rightcolumn_widgets",
		"#middlecolumn_widgets",
		"#leftcolumn_widgets"
	].join(',');
	
	$(cols).droppable({
		accept: ".draggable_widget",
		hoverClass: 'droppable-hover'
	});
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
elgg.ui.menuToggle = function(event) {
	$(this).siblings().slideToggle('medium');
	$(this).toggleClass('elgg-menu-closed elgg-menu-opened');
	event.preventDefault();
}

// reusable generic hidden panel
elgg.ui.toggleCollapsibleBox = function () {
	//$(this.parentNode.parentNode).children(".collapsible_box").slideToggle("fast");
	return false;
};

//define some helper jquery plugins
(function($) {
	
	// ELGG TOOLBAR MENU
	$.fn.elgg_topbardropdownmenu = function(options) {
		var defaults = {
			speed: 350
		};
		
		options = $.extend(defaults, options || {});
	
		this.each(function() {
		
			var root = this, zIndex = 5000;
		
			function getSubnav(ele) {
				if (ele.nodeName.toLowerCase() === 'li') {
					var subnav = $('> ul', ele);
					return subnav.length ? subnav[0] : null;
				} else {
					return ele;
				}
			}
		
			function getActuator(ele) {
				if (ele.nodeName.toLowerCase() === 'ul') {
					return $(ele).parents('li')[0];
				} else {
					return ele;
				}
			}
		
			function hide() {
				var subnav = getSubnav(this);
				if (!subnav) {
					return;
				}
			
				$.data(subnav, 'cancelHide', false);
				setTimeout(function() {
					if (!$.data(subnav, 'cancelHide')) {
						$(subnav).slideUp(100);
					}
				}, 250);
			}
		
			function show() {
				var subnav = getSubnav(this), li;
				
				if (!subnav) {
					return;
				}
				
				$.data(subnav, 'cancelHide', true);
				
				$(subnav).css({zIndex: zIndex}).slideDown(options.speed);
				zIndex++;
				
				if (this.nodeName.toLowerCase() === 'ul') {
					li = getActuator(this);
					$(li).addClass('hover');
					$('> a', li).addClass('hover');
				}
			}
		
			$('ul, li', this).hover(show, hide);
			$('li', this).hover(
				function () { 
					$(this).addClass('hover');
					$('> a', this).addClass('hover');
				},
				function () { 
					$(this).removeClass('hover');
					$('> a', this).removeClass('hover');
				}
			);
		
		});
	};
	
	//Make delimited list
	$.fn.makeDelimitedList = function(elementAttribute) {
	
		var delimitedListArray = [], 
			listDelimiter = "::";
	
		// Loop over each element in the stack and add the elementAttribute to the array
		this.each(function(e) {
				var listElement = $(this);
				// Add the attribute value to our values array
				delimitedListArray[delimitedListArray.length] = listElement.attr(elementAttribute);
			}
		);
	
		// Return value list by joining the array
		return delimitedListArray.join(listDelimiter);
	};
})(jQuery);

elgg.register_event_handler('init', 'system', elgg.ui.init);