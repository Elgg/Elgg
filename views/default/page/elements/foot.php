<?php

echo elgg_view('footer/analytics');

/*
 * Q: Why is all this needed?
 * A: We want require.js to load at the bottom (as all future external scripts
 *    should) and so we need a few things to run in a specific order:
 *
 * 1. Load require.js
 * 2. define() jquery and elgg
 * 3. Run require.config (which may load modules registered by plugins)
 * 4. Fire elgg's [init, system]
 * 5. Any other foot scripts
 */

// We want to configure RequireJS and fire off elgg init as soon as possible
foreach (elgg_get_loaded_js('prefooter') as $src) { ?>
<script src="<?php echo htmlspecialchars($src, ENT_QUOTES, 'UTF-8'); ?>"></script>
<?php
}

$require_config = _elgg_services()->amdConfig->getConfig();
?>
<script>// <![CDATA[
if (typeof define == 'function') {
	define('jquery', [], function() { return jQuery; });
	define('elgg', [], function() { return elgg; });
	require.config(<?php echo json_encode($require_config); ?>);
}
<?php // Require is set up, let's go ?>
$(function() {
	elgg.config.domReady = true;
	elgg.initWhenReady();
});
// ]]></script>
<?php

foreach (elgg_get_loaded_js('footer') as $src) { ?>
<script src="<?php echo htmlspecialchars($src, ENT_QUOTES, 'UTF-8'); ?>"></script>
<?php
}
