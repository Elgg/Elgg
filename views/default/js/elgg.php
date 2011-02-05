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


<?php

$previous_content = elgg_view('js/initialise_elgg');
if ($previous_content) {
	elgg_deprecated_notice("The view 'js/initialise_elgg' has been deprecated for js/elgg", 1.8);
	echo $previous_content;
}
