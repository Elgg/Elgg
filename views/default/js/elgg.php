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
?>

// <script>

elgg.version = '<?php echo get_version(); ?>';
elgg.release = '<?php echo get_version(true); ?>';
elgg.config.wwwroot = '<?php echo elgg_get_site_url(); ?>';
<?php //@todo make this configurable ?>
elgg.security.interval = 5 * 60 * 1000;
elgg.config.language = '<?php echo isset($CONFIG->language) ? $CONFIG->language : 'en'; ?>';

elgg.register_hook_handler('boot', 'system', function() {

	// Once the system has booted, the user language pref has been set,
	// so we can load the correct translations
	var languagesUrl = elgg.config.wwwroot + 'ajax/view/js/languages?language=' + elgg.get_language();
	define('elgg', ['jquery', languagesUrl], function($, translations) {
		elgg.add_translation(elgg.get_language(), translations);
		
		$(function() {
			elgg.trigger_hook('init', 'system');
			elgg.trigger_hook('ready', 'system');		
		});
		
		return elgg;
	});
	require(['elgg']); // Forces the define() function to always run
});

<?php

$previous_content = elgg_view('js/initialise_elgg');
if ($previous_content) {
	elgg_deprecated_notice("The view 'js/initialise_elgg' has been deprecated for js/elgg", 1.8);
	echo $previous_content;
}
