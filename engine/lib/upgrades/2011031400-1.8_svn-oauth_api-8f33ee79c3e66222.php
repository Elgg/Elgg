<?php
/**
 * Elgg 1.8-svn upgrade 2011031400
 * oauth_api
 *
 * Switches oauth_lib to oauth_api
 */

// make sure we have the latest plugin objects.
elgg_generate_plugin_entities();

$old = elgg_get_plugin_from_id('oauth_lib');
$new = elgg_get_plugin_from_id('oauth_api');

if (!$old || !$new) {
	return true;
}

if ($old->isActive()) {
	$old->deactivate();
	$new->activate();
}

$old->delete();