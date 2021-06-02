<?php
/**
 * Remove all user and plugin settings from the give plugin ID
 *
 * @since 3.3
 */

$plugin_id = get_input('plugin_id');
if (empty($plugin_id)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$plugin = elgg_get_plugin_from_id($plugin_id);
if (!$plugin instanceof \ElggPlugin) {
	return elgg_error_response(elgg_echo('PluginException:InvalidID', [$plugin_id]));
}

if (!$plugin->canEdit()) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

if (!$plugin->unsetAllEntityAndPluginSettings()) {
	return elgg_error_response(elgg_echo('plugins:settings:remove:fail', [$plugin->getDisplayName()]));
}

return elgg_ok_response('', elgg_echo('plugins:settings:remove:ok', [$plugin->getDisplayName()]));
