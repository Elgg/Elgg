<?php
/**
 * Core Elgg JavaScript file
 */

use Elgg\Filesystem\Directory;
 
global $CONFIG;

// this warning is due to the change in JS boot order in Elgg 1.9
echo <<<JS
if (typeof elgg != 'object') {
	throw new Error('elgg configuration object is not defined! You must include the js/initialize_elgg view in html head before JS library files!');
}

JS;

// For backwards compatibility...
echo elgg_view('sprintf.js');

// We use a named AMD module and inline it here instead of using an async call.
// This allows us to bootstrap elgg.ui.widgets library at runtime, without having
// to wait for the module to load. This is necessary to ensure BC for plugins that
// rely on elgg.ui.widgets methods to be available at system init.
// @todo: remove in 3.x and use async calls
echo elgg_view('elgg/widgets.js');

$elggDir = \Elgg\Application::elggDir();
$files = array(
	// these must come first
	$elggDir->getPath("js/lib/elgglib.js"),

	// class definitions
	$elggDir->getPath("js/classes/ElggEntity.js"),
	$elggDir->getPath("js/classes/ElggUser.js"),
	$elggDir->getPath("js/classes/ElggPriorityList.js"),

	//libraries
	$elggDir->getPath("js/lib/prototypes.js"),
	$elggDir->getPath("js/lib/hooks.js"),
	$elggDir->getPath("js/lib/security.js"),
	$elggDir->getPath("js/lib/languages.js"),
	$elggDir->getPath("js/lib/ajax.js"),
	$elggDir->getPath("js/lib/session.js"),
	$elggDir->getPath("js/lib/pageowner.js"),
	$elggDir->getPath("js/lib/configuration.js"),
	$elggDir->getPath("js/lib/comments.js"),

	//ui
	$elggDir->getPath("js/lib/ui.js"),
	$elggDir->getPath("js/lib/ui.widgets.js"),
);


foreach ($files as $file) {
	readfile($file);
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

// jQuery and UI must be loaded sync in 2.x but modules should depend on these AMD modules
define('jquery', function () {
	return jQuery;
});
define('jquery-ui');

// The datepicker language modules depend on "../datepicker", so to avoid RequireJS from
// trying to load that, we define it manually here. The lang modules have names like
// "jquery-ui/i18n/datepicker-LANG.min" and these views are mapped in /views.php
define('jquery-ui/datepicker', jQuery.datepicker);

define('elgg', ['jquery', 'languages/' + elgg.get_language()], function($, translations) {
	elgg.add_translation(elgg.get_language(), translations);

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
