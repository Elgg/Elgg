<?php
/**
 * Core Elgg JavaScript file
 */
global $CONFIG;

// this warning is due to the change in JS boot order in Elgg 1.9
echo <<<JS
if (typeof elgg != 'object') {
	throw new Error('elgg configuration object is not defined! You must include the js/initialize_elgg view in html head before JS library files!');
}

JS;


$lib_dir = 'js/lib';
$classes_dir = 'js/classes';
$files = array(
	// these must come first
	'vendor/bower-asset/sprintf/src/sprintf',
	"$lib_dir/elgglib",

	// class definitions
	"$classes_dir/ElggEntity",
	"$classes_dir/ElggUser",
	"$classes_dir/ElggPriorityList",

	//libraries
	"$lib_dir/prototypes",
	"$lib_dir/hooks",
	"$lib_dir/security",
	"$lib_dir/languages",
	"$lib_dir/ajax",
	"$lib_dir/session",
	"$lib_dir/pageowner",
	"$lib_dir/configuration",
	"$lib_dir/comments",

	//ui
	"$lib_dir/ui",
	"$lib_dir/ui.widgets",
);

$root_path = elgg_get_root_path();

foreach ($files as $file) {
	readfile("{$root_path}$file.js");
	// putting a new line between the files to address https://github.com/elgg/elgg/issues/3081
	echo "\n";
}

/**
 * Set some values that are cacheable
 */
?>
//<script>

elgg.version = '<?php echo elgg_get_version(); ?>';
elgg.release = '<?php echo elgg_get_version(true); ?>';
elgg.config.wwwroot = '<?php echo elgg_get_site_url(); ?>';

// refresh token 3 times during its lifetime (in microseconds 1000 * 1/3)
elgg.security.interval = <?php echo (int)_elgg_services()->actions->getActionTokenTimeout() * 333; ?>;
elgg.config.language = '<?php echo (empty($CONFIG->language) ? 'en' : $CONFIG->language); ?>';

// We require jquery-ui because loading the file in an AMD environment doesn't automatically
// apply its functionality to jQuery.
define('elgg', ['jquery', 'languages/' + elgg.get_language()], function($, translations) {
	elgg.add_translation(elgg.get_language(), translations);

	$(function() {
		elgg.trigger_hook('init', 'system');
		elgg.trigger_hook('ready', 'system');
	});

	return elgg;
});

require(['elgg']); // Forces the define() function to always run

// Process queue. We have to wait until now because many modules depend on 'elgg' and we can't load
// it asynchronously.
if (!window._require_queue) {
	if (window.console) {
		console.log('Elgg\'s require() shim is not defined. Do not override the view "page/elements/head".');
	}
} else {
	while (_require_queue.length) {
		require.apply(null, _require_queue.shift());
	}
	delete window._require_queue;
}

elgg.trigger_hook('boot', 'system');
