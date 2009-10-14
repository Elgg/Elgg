<?php
/**
 * Elgg notifications user preference save acion.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

// Method
$method = get_input('method');
gatekeeper();

$result = false;
foreach ($method as $k => $v) {
	$result = set_user_notification_setting($_SESSION['user']->guid, $k, ($v == 'yes') ? true : false);

	if (!$result) {
		register_error(elgg_echo('notifications:usersettings:save:fail'));
	}
}

if ($result) {
	system_message(elgg_echo('notifications:usersettings:save:ok'));
} else {
	register_error(elgg_echo('notifications:usersettings:save:fail'));
}