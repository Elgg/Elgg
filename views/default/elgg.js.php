<?php
/**
 * Core Elgg JavaScript file
 *
 * Includes all code in /engine/js/.
 */

// this warning is due to the change in JS boot order in Elgg 1.9
echo <<<JS
if (typeof elgg != 'object') {
	throw new Error('elgg configuration object is not defined! You must include the js/initialize_elgg view in html head before JS library files!');
}
JS;

// We use named AMD modules and inline them here in order to save HTTP requests,
// as these modules will be required on each page
echo elgg_view('elgg/popup.js');

$elggDir = \Elgg\Application::elggDir()->chroot('engine/js/');
$files = [
	// these must come first
	$elggDir->getPath("elgglib.js"),

	// class definitions
	$elggDir->getPath("ElggEntity.js"),
	$elggDir->getPath("ElggUser.js"),
	$elggDir->getPath("ElggPriorityList.js"),

	//libraries
	$elggDir->getPath("prototypes.js"),
	$elggDir->getPath("hooks.js"),
	$elggDir->getPath("security.js"),
	$elggDir->getPath("languages.js"),
	$elggDir->getPath("ajax.js"),
	$elggDir->getPath("session.js"),
	$elggDir->getPath("pageowner.js"),
	$elggDir->getPath("configuration.js"),
	$elggDir->getPath("comments.js"),

	//ui
	$elggDir->getPath("ui.js"),
];


foreach ($files as $file) {
	readfile($file);
	// putting a new line between the files to address https://github.com/elgg/elgg/issues/3081
	echo "\n";
}

foreach (_elgg_get_js_site_data() as $expression => $value) {
	$value = json_encode($value);
	echo "{$expression} = {$value};\n";
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

elgg.trigger_hook('boot', 'system');

require(['elgg/init', 'elgg/ready', 'elgg/lightbox']);
