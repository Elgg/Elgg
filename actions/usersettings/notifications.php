<?php
/**
 * Save personal notification settings - input comes from request
 */
$user_guid = (int) get_input('guid');
if ($user_guid) {
	$user = get_user($user_guid);
} else {
	$user = elgg_get_logged_in_user_entity();
}
if (!$user) {
	return elgg_error_response(elgg_echo('notifications:usersettings:save:fail'));
}
$method = get_input('method');
$current_settings = $user->getNotificationSettings();
foreach ($method as $k => $v) {
	// check if setting has changed and skip if not
	if ($current_settings[$k] == ($v == 'yes')) {
		continue;
	}
	if (!$user->setNotificationSetting($k, ($v == 'yes'))) {
		return elgg_error_response(elgg_echo('notifications:usersettings:save:fail'));
	}
}
return elgg_ok_response('', elgg_echo('notifications:usersettings:save:ok'));