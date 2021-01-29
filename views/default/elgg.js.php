<?php
/**
 * Core Elgg JavaScript file
 */

$core_js_views = [
	// these must come first
	'elgglib.js',
	
	// class definitions
	'ElggEntity.js',
	'ElggUser.js',
	
	//libraries
	'prototypes.js',
	'hooks.js',
	'security.js',
	'languages.js',
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
elgg.data = $.extend(true, {}, elgg.data, elgg._data);
delete elgg._data;

// jQuery and UI must be loaded sync in 2.x but modules should depend on these AMD modules
define('jquery', function () {
	return jQuery;
});

define('elgg', ['sprintf', 'jquery', 'languages/' + elgg.get_language()], function(vsprintf, $, translations) {
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

elgg.trigger_hook('init', 'system');

require(['elgg/lightbox']);
