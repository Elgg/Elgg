<?php
/**
 * Elgg plugin user settings save action.
 *
 * @package Elgg
 * @subpackage Core
 */

$params = get_input('params');
$plugin = get_input('plugin');

gatekeeper();

$result = false;

foreach ($params as $k => $v) {
	// Save
	$result = set_plugin_usersetting($k, $v, get_loggedin_userid(), $plugin);

	// Error?
	if (!$result) {
		register_error(sprintf(elgg_echo('plugins:usersettings:save:fail'), $plugin));
		forward($_SERVER['HTTP_REFERER']);
		exit;
	}
}

system_message(sprintf(elgg_echo('plugins:usersettings:save:ok'), $plugin));
forward($_SERVER['HTTP_REFERER']);
