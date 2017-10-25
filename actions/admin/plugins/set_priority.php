<?php
/**
 * Changes the load priority of a plugin.
 *
 * Plugin priority affects view, action, and page handler
 * overriding as well as the order of view extensions.  Plugins with higher
 * priority are loaded after and override plugins with lower priorities.
 *
 * NOTE: When viewing the plugin admin page, plugins LOWER on the page
 * have HIGHER priority and will override views, etc from plugins above them.
 */

$plugin_guid = (int) get_input('plugin_guid');
$priority = get_input('priority');

$plugin = get_entity($plugin_guid);

if (!($plugin instanceof ElggPlugin)) {
	return elgg_error_response(elgg_echo('admin:plugins:set_priority:no', [$plugin_guid]));
}

if (!$plugin->setPriority($priority)) {
	$msg = $plugin->getError();
	$string = ($msg) ? 'admin:plugins:set_priority:no_with_msg' : 'admin:plugins:set_priority:no';

	return elgg_error_response(elgg_echo($string, [$plugin->getDisplayName(), $msg]));
}

// don't regenerate the simplecache because the plugin won't be
// loaded until next run.  Just invalidate and let it regnerate as needed
elgg_flush_caches();

return elgg_ok_response();
