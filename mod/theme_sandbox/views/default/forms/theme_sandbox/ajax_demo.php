<?php
/**
 * This will be fetched via ajax by the theme_sandbox/demo/ajax_example module
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggSite) {
	elgg_register_error_message('$vars not set by ajax.view()');
}

echo 'form demo';
