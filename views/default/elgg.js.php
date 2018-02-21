<?php
/**
 * Core Elgg JavaScript file
 */

// We use named AMD modules and inline them here in order to save HTTP requests,
// as these modules will be required on each page
echo elgg_view('elgg/popup.js');

$core_js_views = [
	// these must come first
	'elgglib.js',
	
	// class definitions
	'ElggEntity.js',
	'ElggUser.js',
	'ElggPriorityList.js',
	
	//libraries
	'prototypes.js',
	'hooks.js',
	'security.js',
	'languages.js',
	'ajax.js',
	'session.js',
	'pageowner.js',
	'configuration.js',
	
	//ui
	'ui.js',
];

foreach ($core_js_views as $view) {
	echo elgg_view("core/js/{$view}");
	// putting a new line between the files to address https://github.com/elgg/elgg/issues/3081
	echo PHP_EOL;
}

foreach (_elgg_get_js_site_data() as $expression => $value) {
	$value = json_encode($value);
	echo "{$expression} = {$value};" . PHP_EOL;
}
?>
//<script>

// page data overrides site data
$.extend(elgg.data, elgg._data);
delete elgg._data;

// jQuery and UI must be loaded sync in 2.x but modules should depend on these AMD modules
define('jquery', function () {
	return jQuery;
});
define('jquery-ui');

// The datepicker language modules depend on "../datepicker", so to avoid RequireJS from
// trying to load that, we define it manually here. The lang modules have names like
// "jquery-ui/i18n/datepicker-LANG.min" and these views are mapped in /views.php
define('jquery-ui/datepicker', jQuery.datepicker);

define('elgg', ['sprintf', 'jquery', 'languages/' + elgg.get_language(), 'weakmap-polyfill', 'formdata-polyfill'], function(vsprintf, $, translations) {
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

require(['elgg/init', 'elgg/ready', 'elgg/lightbox']);
