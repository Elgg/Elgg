<?php
/**
 * Core Elgg JavaScript file
 */

echo elgg_view('core/js/elgglib.js');
echo elgg_view('core/js/deprecated.js');
echo elgg_view('core/js/hooks.js');
echo elgg_view('core/js/ui.js');

foreach (_elgg_get_js_site_data() as $expression => $value) {
	$value = json_encode($value);
	echo "{$expression} = {$value};" . PHP_EOL;
}
?>

// page data overrides site data
elgg.data = $.extend(true, {}, elgg.data, elgg._data);
delete elgg._data;

// jQuery and UI must be loaded sync in 2.x but modules should depend on these AMD modules
define('jquery', function () {
	return jQuery;
});

define('elgg', [], function() {
	return elgg;
});

// @todo no longer require elgg/i18n in Elgg 5.0
require(['elgg', 'elgg/i18n']); // Forces the define() function to always run

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

require(['elgg/lightbox', 'elgg/security']);
