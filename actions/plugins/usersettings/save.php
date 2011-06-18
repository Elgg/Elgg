<?php
/**
 * Saves user-specific plugin settings.
 *
 * This action can be overriden for a specific plugin by creating the
 * settings/<plugin_id>/save action in that plugin.
 *
 * @uses array $_REQUEST['params']    A set of key/value pairs to save to the ElggPlugin entity
 * @uses int   $_REQUEST['plugin_id'] The id of the plugin
 * @uses int   $_REQUEST['user_guid'] The GUID of the user to save settings for.
 *
 * @package Elgg.Core
 * @subpackage Plugins.Settings
 */

$params = get_input('params');
$plugin_id = get_input('plugin_id');
$user_guid = get_input('user_guid', elgg_get_logged_in_user_guid());
$plugin = elgg_get_plugin_from_id($plugin_id);
$user = get_entity($user_guid);

if (!($plugin instanceof ElggPlugin)) {
	register_error(elgg_echo('plugins:usersettings:save:fail', array($plugin_id)));
	forward(REFERER);
}

if (!($user instanceof ElggUser)) {
	register_error(elgg_echo('plugins:usersettings:save:fail', array($plugin_id)));
	forward(REFERER);
}

$plugin_name = $plugin->getManifest()->getName();

// make sure we're admin or the user
if (!$user->canEdit()) {
	register_error(elgg_echo('plugins:usersettings:save:fail', array($plugin_name)));
	forward(REFERER);
}

$result = false;

if (elgg_action_exists("usersettings/$plugin_id/save")) {
	action("usersettings/$plugin_id/save");
} else {
	foreach ($params as $k => $v) {
		// Save
		$result = $plugin->setUserSetting($k, $v, $user->guid);

		// Error?
		if (!$result) {
			register_error(elgg_echo('plugins:usersettings:save:fail', array($plugin_name)));
			forward(REFERER);
		}
	}
}

system_message(elgg_echo('plugins:usersettings:save:ok', array($plugin_name)));
forward(REFERER);
