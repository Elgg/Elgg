<?php
/**
 * Saves user notification settings
 */

$guid = (int) get_input('guid');

$user = get_user($guid);
if (!$user instanceof ElggUser || !$user->canEdit()) {
	return elgg_error_response(elgg_echo('actionunauthorized'), '', 403);
}

$methods = elgg_get_notification_methods();
if (empty($methods)) {
	return elgg_error_response('', REFERER, 404);
}

$notification_settings = (array) get_input('notification_setting', []);
foreach ($notification_settings as $purpose => $prefered_methods) {
	if (!is_array($prefered_methods)) {
		$prefered_methods = [];
	}
	
	foreach ($methods as $method) {
		$user->setNotificationSetting($method, in_array($method, $prefered_methods), $purpose);
	}
}

return elgg_ok_response('', elgg_echo('notifications:subscriptions:success'));
