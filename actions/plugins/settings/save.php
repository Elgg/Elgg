<?php
/**
 * Saves global plugin settings.
 *
 * This action can be overriden for a specific plugin by creating the
 * <plugin_id>/settings/save action in that plugin.
 *
 * @uses array $_REQUEST['params']      A set of key/value pairs to save to the ElggPlugin entity
 * @uses mixed $_REQUEST['flush_cache'] If a truthy value is provided the caches will be flushed
 * @uses int   $_REQUEST['plugin_id']   The ID of the plugin
 */

$params = get_input('params');
$flush_cache = get_input('flush_cache');
$plugin_id = get_input('plugin_id');
$plugin = elgg_get_plugin_from_id($plugin_id);

if (!$plugin) {
	return elgg_error_response(elgg_echo('plugins:settings:save:fail', [$plugin_id]));
}

$plugin_name = $plugin->getDisplayName();

$result = false;

foreach ($params as $k => $v) {
	$result = $plugin->setSetting($k, $v);
	if (!$result) {
		return elgg_error_response(elgg_echo('plugins:settings:save:fail', [$plugin_name]));
	}
}

if ($flush_cache) {
	elgg_flush_caches();
}

return elgg_ok_response('', elgg_echo('plugins:settings:save:ok', [$plugin_name]));
