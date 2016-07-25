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

// For backwards compatibility...
echo elgg_view('sprintf.js');

// We use a named AMD module and inline it here instead of using an async call.
// This allows us to bootstrap elgg.ui.widgets library at runtime, without having
// to wait for the module to load. This is necessary to ensure BC for plugins that
// rely on elgg.ui.widgets methods to be available at system init.
// @todo: remove in 3.x and use async calls
echo elgg_view('elgg/widgets.js');

// In 3.0 this will be required by elgg/lightbox, but in 2.x we have to worry about
// legacy code that expects $.colorbox to be ready synchronously. To avoid inlining
// in both lightbox.js and elgg/lightbox, we do so here and define it as a module.
echo elgg_view('jquery.colorbox.js');
?>
define('jquery.colorbox');
<?php
// We use named AMD modules and inline them here in order to save HTTP requests,
// as these modules will be required on each page
echo elgg_view('elgg/popup.js');
echo elgg_view('elgg/lightbox.js');

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

?>
//<script>
<?php foreach (_elgg_get_js_site_data() as $expression => $value): ?>
<?= $expression ?> = <?= json_encode($value) ?>;
<?php endforeach; ?>

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

require(['elgg/init', 'elgg/ready']);

<?php
if (_elgg_view_may_be_altered('lightbox/settings.js', 'lightbox/settings.js.php')) {
	elgg_deprecated_notice('lightbox/settings.js view has been deprecated. Use "getOptions", "ui.lightbox" ' .
		'JS plugin hook or data-colorbox-opts attribute instead', '2.2');
	?>
	require(['elgg'], function(elgg) {
		elgg.provide('elgg.ui.lightbox');
		<?= elgg_view('lightbox/settings.js') ?>
	});
	<?php
}
?>

// We need to ensure bindings take place
require(['elgg/lightbox']);