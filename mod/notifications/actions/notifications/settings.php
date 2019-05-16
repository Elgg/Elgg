<?php
/**
 * Saves user notification settings
 */

$guid = get_input('guid');

$user = get_user($guid);
if (!$user instanceof ElggUser || !$user->canEdit()) {
	return elgg_error_response(elgg_echo('actionunauthorized'), '', 403);
}

$methods = elgg_get_notification_methods();
if (empty($methods)) {
	return elgg_error_response('', REFERER, 404);
}

$personal_settings = (array) get_input('personal', []);
foreach ($methods as $method) {
	$user->setNotificationSetting($method, in_array($method, $personal_settings));
}

$collection_settings = get_input('collections');
if ($collection_settings !== null) {
	$collections_by_method = [];
	
	if (!empty($collection_settings)) {
		foreach ($collection_settings as $collection_id => $preferred_methods) {
			if (!is_array($preferred_methods)) {
				$preferred_methods = [];
			}
			foreach ($preferred_methods as $preferred_method) {
				$collections_by_method[$preferred_method][] = $collection_id;
			}
		}
	}

	foreach ($methods as $method) {
		$metaname = 'collections_notifications_preferences_' . $method;
		
		if (!isset($collections_by_method[$method])) {
			// no collections for this method
			unset($user->$metaname);
			continue;
		}
		
		$user->$metaname = array_unique($collections_by_method[$method]);
	}
}

return elgg_ok_response('', elgg_echo('notifications:subscriptions:success'));
