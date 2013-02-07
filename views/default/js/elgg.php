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
	'hooks',
	'security',
	'languages',
	'ajax',
	'session',
	'pageowner',
	'configuration',

	//ui
	'ui',
	'ui.widgets',
);

foreach ($libs as $file) {
	include("{$CONFIG->path}js/lib/$file.js");
	// putting a new line between the files to address http://trac.elgg.org/ticket/3081
	echo "\n";
}

/**
 * Set some values that are cacheable
 */
if (0) { ?><script><?php }
?>

elgg.version = '<?php echo get_version(); ?>';
elgg.release = '<?php echo get_version(true); ?>';
elgg.config.wwwroot = '<?php echo elgg_get_site_url(); ?>';
<?php //@todo make this configurable ?>
elgg.security.interval = 5 * 60 * 1000;
elgg.config.domReady = false;
elgg.config.language = '<?php echo isset($CONFIG->language) ? $CONFIG->language : 'en'; ?>';
elgg.config.languageReady = false;

//After the DOM is ready
$(function() {
	elgg.config.domReady = true;
	elgg.initWhenReady();
});

<?php

$previous_content = elgg_view('js/initialise_elgg');
if ($previous_content) {
	elgg_deprecated_notice("The view 'js/initialise_elgg' has been deprecated for js/elgg", 1.8);
	echo $previous_content;
}
