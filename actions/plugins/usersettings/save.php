<?php
/**
 * Elgg plugin user settings save action.
 *
 * @package Elgg
 * @subpackage Core
 */

$params = get_input('params');
$plugin = get_input('plugin');

$result = false;

foreach ($params as $k => $v) {
	// Save
	$result = set_plugin_usersetting($k, $v, get_loggedin_userid(), $plugin);

	// Error?
	if (!$result) {
		register_error(elgg_echo('plugins:usersettings:save:fail', array($plugin)));
		forward(REFERER);
		exit;
	}
}

system_message(elgg_echo('plugins:usersettings:save:ok', array($plugin)));
forward(REFERER);
