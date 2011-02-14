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

<?php

$previous_content = elgg_view('js/initialise_elgg');
if ($previous_content) {
	elgg_deprecated_notice("The view 'js/initialise_elgg' has been deprecated for js/elgg", 1.8);
	echo $previous_content;
}
