/**
 * Core Elgg JavaScript file
 *
 * @internal Do not alter or extend this view. Attempts to extend it will be instead applied to
 *           elgg/sync_code.js, which is loaded at the bottom.
 *
 * @see views/default/page/elements/head.php Defines a fake require() for sync scripts in body
 * @see views/default/page/elements/foot.php Completes the boot process
 */
define(function (require) {
	var $ = require('jquery');
	var jQuery = $;

	if (typeof window.elgg != 'object') {
		throw new Error('elgg configuration object is not defined! You must not alter the page/elements/foot view!');
	}

	<?= elgg_view('sprintf.js'); ?>
	// BC with scripts who expect these to be global
	window.sprintf = sprintf;
	window.vsprintf = vsprintf;

<?php

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

$system_language = elgg_get_config('language');
if (!$system_language) {
	$system_language = 'en';
}

$token_timeout = (int)_elgg_services()->actions->getActionTokenTimeout() * 333;
$data = (object)elgg_trigger_plugin_hook('elgg.data', 'site', null, []);

?>
//<script>
	// page data overrides site data
	elgg.data = $.extend(<?= json_encode($data) ?>, elgg._data);
	delete elgg._data;

	elgg.version = '<?php echo elgg_get_version(); ?>';
	elgg.release = '<?php echo elgg_get_version(true); ?>';
	elgg.config.wwwroot = '<?php echo elgg_get_site_url(); ?>';

	// refresh token 3 times during its lifetime (in microseconds 1000 * 1/3)
	elgg.security.interval = <?= $token_timeout ?>;
	elgg.config.language = '<?= $system_language ?>';

	// load current user's language
	elgg.echo_ready();

	elgg.trigger_hook('boot', 'system');

	// See elgg_extend_view()
	if (elgg.config.load_sync_code) {
		$.ajax({
			url: elgg.get_simplecache_url('elgg/sync_code.js'),
			dataType: 'script',
			cache: true
		});
	}

	return elgg;
});
