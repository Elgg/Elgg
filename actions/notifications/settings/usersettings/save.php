<?php
/**
 * Elgg notifications user preference save acion.
 *
 * @package Elgg
 * @subpackage Core
 */

// Method
$method = get_input('method');
gatekeeper();

$current_settings = get_user_notification_settings();

$result = false;
foreach ($method as $k => $v) {
	// check if setting has changed and skip if not
	if ($current_settings->$k == ($v == 'yes')) {
		continue;
	}

	$result = set_user_notification_setting(get_loggedin_userid(), $k, ($v == 'yes') ? true : false);

	if (!$result) {
		register_error(elgg_echo('notifications:usersettings:save:fail'));
	}
}

if ($result) {
	system_message(elgg_echo('notifications:usersettings:save:ok'));
}
