<?php
/**
 * Elgg 1.8-svn upgrade 2011031400
 * oauth_api
 *
 * Switches oauth_lib to oauth_api
 */

$ia = elgg_set_ignore_access(true);

// make sure we have updated plugins
elgg_generate_plugin_entities();

$show_hidden = access_get_show_hidden_status();
access_show_hidden_entities(true);

$old = elgg_get_plugin_from_id('oauth_lib');
$new = elgg_get_plugin_from_id('oauth_api');

if (!$old || !$new) {
	return true;
}

$old->deactivate();
$old->delete();

elgg_add_admin_notice('oauth_api:disabled', elgg_echo('update:oauth_api:deactivated'));

access_show_hidden_entities($show_hidden);
elgg_set_ignore_access($ia);
