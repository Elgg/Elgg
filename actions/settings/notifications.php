<?php
/**
 * Saves user notification settings
 */

use Elgg\Values;

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

// timed muting
$start = (int) get_input('timed_muting_start');
$end = (int) get_input('timed_muting_end');
if (!empty($start) && !empty($end) && $start <= $end) {
	// change end to [date 23:59:59] instead of [date 00:00:00]
	$end_date = Values::normalizeTime($end);
	$end_date->setTime(23, 59, 59);
	$end = $end_date->getTimestamp();
	
	$user->setPrivateSetting('timed_muting_start', $start);
	$user->setPrivateSetting('timed_muting_end', $end);
} else {
	$user->removePrivateSetting('timed_muting_start');
	$user->removePrivateSetting('timed_muting_end');
}

return elgg_ok_response('', elgg_echo('usersettings:notifications:save:ok'));
