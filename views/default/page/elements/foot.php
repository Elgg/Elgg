<?php
/**
 * Elgg's JS setup.
 *
 * @internal It's dangerous to alter this view.
 */

// 0. a fake require() shim was defined in the page/elements/head view to delay processing.

$init_data = _elgg_get_init_client_data();
$modules = _elgg_services()->amdConfig->getDependencies();

// 1. define elgg with page/session-specific data. We do this early so the async "elgg" module
// can be loaded with no problem.
?>
<script>
var elgg = <?= json_encode($init_data); ?>;
</script>
<?php

// 2. Sync script elements. This includes jQuery, UI, most other scripts enabled with elgg_load_js()
// and finally RequireJS. Require is is last so that sync scripts use Elgg's require() shim.
// @see elgg_views_boot
foreach (['head', 'footer', 'require'] as $location) {
	foreach (elgg_get_loaded_js($location) as $url) {
		echo elgg_format_element('script', array('src' => $url));
	}
}

// 3. Allows these already synchronously loaded libs to be used as AMD modules
// The datepicker language modules depend on "../datepicker", so to avoid RequireJS from
// trying to load that, we define it manually here. The lang modules have names like
// "jquery-ui/i18n/datepicker-LANG.min" and these views are mapped in /engine/views.php
?>
<script>
define('jquery', function () {
	return jQuery;
});
define('jquery-ui');
define('jquery-ui/datepicker', jQuery.datepicker);
</script>
<?php

// 4. Scripts that we want loaded synchronously *after* require/define are set up.
foreach (elgg_get_loaded_js('amd') as $url) {
	echo elgg_format_element('script', array('src' => $url));
}

// 5. Apply the shimmed require() calls to the real require().
?>
<script>
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

<?php
// 6. load modules requested with elgg_require_js()
?>
require(<?= json_encode($modules, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>);
</script>
