<?php
/**
 * Saves user-specific plugin settings.
 *
 * This action can be overriden for a specific plugin by creating the
 * <plugin_id>/usersettings/save action in that plugin.
 *
 * @uses array $_REQUEST['params']    A set of key/value pairs to save to the ElggPlugin entity
 * @uses int   $_REQUEST['plugin_id'] The id of the plugin
 * @uses int   $_REQUEST['user_guid'] The GUID of the user to save settings for.
 */

$params = get_input('params');
$plugin_id = get_input('plugin_id');
$user_guid = (int) get_input('user_guid', elgg_get_logged_in_user_guid());
$plugin = elgg_get_plugin_from_id($plugin_id);
$user = get_user($user_guid);

if (!$plugin || !$user || !$user->canEdit()) {
	return elgg_error_response(elgg_echo('plugins:usersettings:save:fail', [$plugin_id]));
}

$plugin_name = $plugin->getDisplayName();

$result = false;

foreach ($params as $name => $value) {
	$result = $user->setPluginSetting($plugin->getID(), $name, $value);
	if (!$result) {
		return elgg_error_response(elgg_echo('plugins:usersettings:save:fail', [$plugin_name]));
	}
}

return elgg_ok_response('', elgg_echo('plugins:usersettings:save:ok', [$plugin_name]));
