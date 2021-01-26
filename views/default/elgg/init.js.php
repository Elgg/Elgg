<?php
/**
 * Boot all the plugins and trigger the [init, system] hook
 *
 * Depend on this module to guarantee all [init, system] handlers have been called
 */
?>
//<script>
define(['elgg'], function (elgg) {
	elgg.trigger_hook('init', 'system');
});
