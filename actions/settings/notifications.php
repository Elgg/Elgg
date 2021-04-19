<?php
/**
 * Saves user notification settings
 */

$guid = (int) get_input('guid');

$user = get_user($guid);
if (!$user instanceof ElggUser || !$user->canEdit()) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

$methods = elgg_get_notification_methods();
if (empty($methods)) {
	return elgg_error_response(elgg_echo('usersettings:notifications:save:fail'));
}

// notification settings
$notification_settings = (array) get_input('notification_setting', []);
foreach ($notification_settings as $purpose => $prefered_methods) {
	if (!is_array($prefered_methods)) {
		$prefered_methods = [];
	}
	
	foreach ($methods as $method) {
		$user->setNotificationSetting($method, in_array($method, $prefered_methods), $purpose);
	}
}

// delayed email interval
if ((bool) elgg_get_config('enable_delayed_email')) {
	$delayed_email_interval = get_input('delayed_email_interval');
	if ($user->getPrivateSetting('delayed_email_interval') !== $delayed_email_interval) {
		// save new setting
		$user->setPrivateSetting('delayed_email_interval', $delayed_email_interval);
		
		// update all queued notifications to the new interval
		_elgg_services()->delayedEmailQueueTable->updateRecipientInterval($user->guid, $delayed_email_interval);
	}
}

return elgg_ok_response('', elgg_echo('usersettings:notifications:save:ok'));
