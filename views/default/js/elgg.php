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


// reusable slide in/out toggle function
function elgg_slide_toggle(activateLink, parentElement, toggleElement) {
	$(activateLink).closest(parentElement).find(toggleElement).animate({"height": "toggle"}, { duration: 400 });
	return false;
}

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


var submenuLayer = 1000;

function setup_avatar_menu(parent) {
	if (!parent) {
		parent = document;
	}

	// avatar image menu link
	$(parent).find("div.elgg-user-icon img").mouseover(function() {
		// find nested avatar_menu_button and show
		$(this.parentNode.parentNode).children(".avatar_menu_button").show();
		$(this.parentNode.parentNode).children("div.avatar_menu_button").addClass("avatar_menu_arrow");
		//$(this.parentNode.parentNode).css("z-index", submenuLayer);
	})
	.mouseout(function() {
		if($(this).parent().parent().find("div.sub_menu").css('display')!="block") {
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow");
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow_on");
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow_hover");
			$(this.parentNode.parentNode).children(".avatar_menu_button").hide();
		}
		else {
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow");
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow_on");
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow_hover");
			$(this.parentNode.parentNode).children(".avatar_menu_button").show();
			$(this.parentNode.parentNode).children("div.avatar_menu_button").addClass("avatar_menu_arrow");
		}
	});


	// avatar contextual menu
	$(".avatar_menu_button img").click(function(e) {

		//var submenu = $(this).parent().parent().find("div.sub_menu");
		var submenu = $(this).parent().parent().find(".elgg-hover-menu");

		// close submenu if arrow is clicked & menu already open
		if (submenu.css('display') == "block") {
			//submenu.hide();
		} else {
			// get avatar dimensions
			var avatar = $(this).parent().parent().parent().find("div.elgg-user-icon");
			//alert( "avatarWidth: " + avatar.width() + ", avatarHeight: " + avatar.height() );

			// move submenu position so it aligns with arrow graphic
			if (e.pageX < 840) { // popup menu to left of arrow if we're at edge of page
				submenu.css("top",(avatar.height()) + "px")
					.css("left",(avatar.width()-15) + "px")
					.fadeIn('normal');
			} else {
				submenu.css("top",(avatar.height()) + "px")
					.css("left",(avatar.width()-166) + "px")
					.fadeIn('normal');
			}

			// force z-index - workaround for IE z-index bug
			avatar.css("z-index",  submenuLayer);
			avatar.find("a.icon img").css("z-index",  submenuLayer);
			submenu.css("z-index", submenuLayer+1);

			submenuLayer++;

			// change arrow to 'on' state
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow");
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow_hover");
			$(this.parentNode.parentNode).children("div.avatar_menu_button").addClass("avatar_menu_arrow_on");
		}

		// hide any other open submenus and reset arrows
		$("div.sub_menu:visible").not(submenu).hide();
		$(".avatar_menu_button").removeClass("avatar_menu_arrow");
		$(".avatar_menu_button").removeClass("avatar_menu_arrow_on");
		$(".avatar_menu_button").removeClass("avatar_menu_arrow_hover");
		$(".avatar_menu_button").hide();
		$(this.parentNode.parentNode).children("div.avatar_menu_button").addClass("avatar_menu_arrow_on");
		$(this.parentNode.parentNode).children("div.avatar_menu_button").show();
		//alert("submenuLayer = " +submenu.css("z-index"));
	})
	// hover arrow each time mouseover enters arrow graphic (eg. when menu is already shown)
	.mouseover(function() {
		$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow_on");
		$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow");
		$(this.parentNode.parentNode).children("div.avatar_menu_button").addClass("avatar_menu_arrow_hover");
	})
	// if menu not shown revert arrow, else show 'menu open' arrow
	.mouseout(function() {
		if($(this).parent().parent().find("div.sub_menu").css('display')!="block"){
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow_hover");
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow");
			$(this.parentNode.parentNode).children("div.avatar_menu_button").addClass("avatar_menu_arrow");
		}
		else {
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow_hover");
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow");
			$(this.parentNode.parentNode).children("div.avatar_menu_button").addClass("avatar_menu_arrow_on");
		}
	});

	// hide avatar menu if click occurs outside of menu
	// and hide arrow button
	$(document).click(function(event) {
			var target = $(event.target);
			if (target.parents(".elgg-user-icon").length == 0) {
				$(".elgg-hover-menu").fadeOut();
				$(".avatar_menu_button").removeClass("avatar_menu_arrow");
				$(".avatar_menu_button").removeClass("avatar_menu_arrow_on");
				$(".avatar_menu_button").removeClass("avatar_menu_arrow_hover");
				$(".avatar_menu_button").hide();
			}
	});


}

$(document).ready(function() {

	setup_avatar_menu();

});


<?php

$previous_content = elgg_view('js/initialise_elgg');
if ($previous_content) {
	elgg_deprecated_notice("The view 'js/initialise_elgg' has been deprecated for js/elgg", 1.8);
	echo $previous_content;
}
