<?php
/**
 * Core Elgg javascript loader
 */
global $CONFIG;

$prereq_files = array(
	"vendors/sprintf.js",
	"js/lib/elgglib.js",
);

foreach ($prereq_files as $file) {
	include("{$CONFIG->path}$file");
}

//No such thing as autoloading classes in javascript
$model_files = array(
	'ElggEntity',
	'ElggUser',
	'ElggPriorityList',
);

foreach ($model_files as $file) {
	include("{$CONFIG->path}js/classes/$file.js");
}

//Include library files
$libs = array(
	//libraries
	'prototypes',
	'events',
	'security',
	'languages',
	'ajax',
	'session',

	//ui
	'ui',
	'ui.widgets',
);

foreach ($libs as $file) {
	include("{$CONFIG->path}js/lib/$file.js");
}

/**
 * Set some values that are cacheable
 */
?>

elgg.version = '<?php echo get_version(); ?>';
elgg.release = '<?php echo get_version(true); ?>';
elgg.config.wwwroot = '<?php echo elgg_get_site_url(); ?>';
elgg.security.interval = 5 * 60 * 1000; <?php //@todo make this configurable ?>

//Mimic PHP engine boot process

//Before the DOM is ready -- note that plugins aren't loaded yet
elgg.trigger_event('boot', 'system');

//After the DOM is ready
$(function() {
	elgg.trigger_event('init', 'system');
});

$(document).ready(function () {

	// COLLAPSABLE WIDGETS (on Dashboard? & Profile pages)
	// toggle widget box contents
	$('a.toggle_box_contents').bind('click', toggleContent);

	// WIDGET GALLERY EDIT PANEL
	// Sortable widgets
	var els = ['#leftcolumn_widgets', '#middlecolumn_widgets', '#rightcolumn_widgets', '#widget_picker_gallery' ];
	var $els = $(els.toString());

	$els.sortable({
		items: '.draggable_widget',
		handle: '.drag-handle',
		forcePlaceholderSize: true,
		placeholder: 'ui-state-highlight',
		cursor: 'move',
		revert: true,
		opacity: 0.9,
		appendTo: 'body',
		connectWith: els,
		start:function(e,ui) {

		},
		stop: function(e,ui) {
			// refresh list before updating hidden fields with new widget order
			$(this).sortable( "refresh" );

			var widgetNamesLeft = outputWidgetList('#leftcolumn_widgets');
			var widgetNamesMiddle = outputWidgetList('#middlecolumn_widgets');
			var widgetNamesRight = outputWidgetList('#rightcolumn_widgets');

			document.getElementById('debugField1').value = widgetNamesLeft;
			document.getElementById('debugField2').value = widgetNamesMiddle;
			document.getElementById('debugField3').value = widgetNamesRight;
		}
	});

	// bind more info buttons - called when new widgets are created
	widget_moreinfo();

	// set-up hover class for dragged widgets
	$("#rightcolumn_widgets").droppable({
		accept: ".draggable_widget",
		hoverClass: 'droppable-hover'
	});
	$("#middlecolumn_widgets").droppable({
		accept: ".draggable_widget",
		hoverClass: 'droppable-hover'
	});
	$("#leftcolumn_widgets").droppable({
		accept: ".draggable_widget",
		hoverClass: 'droppable-hover'
	});

	// user likes
	$(".likes-list-button").click(function(event) {
		if ($(this).next(".likes-list").css('display') == 'none') {	// show list
			// hide any other currently viewable likes lists
			$('.likes-list').fadeOut();

			var topPosition = - $(this).next(".likes-list").height();
			topPosition10 = topPosition + 10 + "px";
			topPosition = topPosition - 5 + "px";
			$('.likes-list').css('top',topPosition10);
			$('.likes-list').css('left', -$('.likes-list').width()+110);
			$(this).next(".likes-list").animate({opacity: "toggle", top: topPosition}, 500);

			// set up cancel for a click outside the likes list
			$(document).click(function(event) {
					var target = $(event.target);
					if (target.parents(".likes-list-holder").length == 0) {
						$(".likes-list").fadeOut();
					}
			});

		} else { // hide list
			var topPosition = - $(this).next(".likes-list").height() + 5;
			$(this).next(".likes-list").animate({opacity: "toggle", top: topPosition}, 500);
		}
	});

	elgg_system_message();

}); /* end document ready function */



// display & hide elgg system messages
function elgg_system_message() {
	$("#elgg-system-message").animate({opacity: 0.9}, 1000);
	$("#elgg-system-message").animate({opacity: 0.9}, 5000);
	$("#elgg-system-message").fadeOut('slow');

	$("#elgg-system-message").click(function () {
		$("#elgg-system-message").stop();
		$("#elgg-system-message").fadeOut('slow');
	return false;
	});
}

// reusable slide in/out toggle function
function elgg_slide_toggle(activateLink, parentElement, toggleElement) {
	$(activateLink).closest(parentElement).find(toggleElement).animate({"height": "toggle"}, { duration: 400 });
	return false;
}

// List active widgets for each page column
function outputWidgetList(forElement) {
	return( $("input[name='handler'], input[name='guid']", forElement ).makeDelimitedList("value") );
}

// Make delimited list
jQuery.fn.makeDelimitedList = function(elementAttribute) {

	var delimitedListArray = new Array();
	var listDelimiter = "::";

	// Loop over each element in the stack and add the elementAttribute to the array
	this.each(function(e) {
			var listElement = $(this);
			// Add the attribute value to our values array
			delimitedListArray[delimitedListArray.length] = listElement.attr(elementAttribute);
		}
	);

	// Return value list by joining the array
	return(delimitedListArray.join(listDelimiter));
}


// Read each widgets collapsed/expanded state from cookie and apply
function widget_state(forWidget) {

	var thisWidgetState = $.cookie(forWidget);

	if (thisWidgetState == 'collapsed') {
		forWidget = "#" + forWidget;
		$(forWidget).find("div.collapsable_box_content").hide();
		$(forWidget).find("a.toggle_box_contents").html('+');
		$(forWidget).find("a.toggle_box_edit_panel").fadeOut('medium');
	};
}


// Toggle widgets contents and save to a cookie
var toggleContent = function(e) {
var targetContent = $('div.collapsable_box_content', this.parentNode.parentNode);
	if (targetContent.css('display') == 'none') {
		targetContent.slideDown(400);
		$(this).html('-');
		$(this.parentNode).children(".toggle_box_edit_panel").fadeIn('medium');

		// set cookie for widget panel open-state
		var thisWidgetName = $(this.parentNode.parentNode.parentNode).attr('id');
		$.cookie(thisWidgetName, 'expanded', { expires: 365 });

	} else {
		targetContent.slideUp(400);
		$(this).html('+');
		$(this.parentNode).children(".toggle_box_edit_panel").fadeOut('medium');
		// make sure edit pane is closed
		$(this.parentNode.parentNode).children(".collapsable_box_editpanel").hide();

		// set cookie for widget panel closed-state
		var thisWidgetName = $(this.parentNode.parentNode.parentNode).attr('id');
		$.cookie(thisWidgetName, 'collapsed', { expires: 365 });
	}
	return false;
};

// More info tooltip in widget gallery edit panel
function widget_moreinfo() {

	$("img.more_info").hover(function(e) {
	var widgetdescription = $("input[name='description']", this.parentNode.parentNode.parentNode ).attr('value');
	$("body").append("<p id='widget_moreinfo'><b>"+ widgetdescription +" </b></p>");

		if (e.pageX < 900) {
			$("#widget_moreinfo")
				.css("top",(e.pageY + 10) + "px")
				.css("left",(e.pageX + 10) + "px")
				.fadeIn("medium");
		}
		else {
			$("#widget_moreinfo")
				.css("top",(e.pageY + 10) + "px")
				.css("left",(e.pageX - 210) + "px")
				.fadeIn("medium");
		}
	},
	function() {
		$("#widget_moreinfo").remove();
	});

	$("img.more_info").mousemove(function(e) {
		// action on mousemove
	});
};

// ELGG DROP DOWN MENU
$.fn.elgg_dropdownmenu = function(options) {

options = $.extend({speed: 350}, options || {});

this.each(function() {

	var root = this, zIndex = 5000;

	function getSubnav(ele) {
	if (ele.nodeName.toLowerCase() == 'li') {
		var subnav = $('> ul', ele);
		return subnav.length ? subnav[0] : null;
	} else {

		return ele;
	}
	}

	function getActuator(ele) {
	if (ele.nodeName.toLowerCase() == 'ul') {
		return $(ele).parents('li')[0];
	} else {
		return ele;
	}
	}

	function hide() {
	var subnav = getSubnav(this);
	if (!subnav) return;
	$.data(subnav, 'cancelHide', false);
	setTimeout(function() {
		if (!$.data(subnav, 'cancelHide')) {
		$(subnav).slideUp(100);
		}
	}, 250);
	}

	function show() {
	var subnav = getSubnav(this);
	if (!subnav) return;
	$.data(subnav, 'cancelHide', true);
	$(subnav).css({zIndex: zIndex++}).slideDown(options.speed);
	if (this.nodeName.toLowerCase() == 'ul') {
		var li = getActuator(this);
		$(li).addClass('hover');
		$('> a', li).addClass('hover');
	}
	}

	$('ul, li', this).hover(show, hide);
	$('li', this).hover(
	function() { $(this).addClass('hover'); $('> a', this).addClass('hover'); },
	function() { $(this).removeClass('hover'); $('> a', this).removeClass('hover'); }
	);

});

};

<?php

$previous_content = elgg_view('js/initialise_elgg');
if ($previous_content) {
	elgg_deprecated_notice("The view 'js/initialise_elgg' has been deprecated for js/elgg", 1.8);
	echo $previous_content;
}
