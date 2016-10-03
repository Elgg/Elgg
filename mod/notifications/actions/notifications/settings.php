<?php

/**
 * Saves user notification settings
 */

$guid = get_input('guid');
$user = get_entity($guid);

if (!$user || !$user->canEdit()) {
	register_error(elgg_echo('actionnotauthorized'));
	forward('', '403');
}

$methods = elgg_get_notification_methods();
if (empty($methods)) {
	forward(REFERRER, '404');
}

$personal_settings = (array) get_input('personal', []);
foreach ($methods as $method) {
	$user->setNotificationSetting($method, in_array($method, $personal_settings));
}

$collections_by_method = [];

$collection_settings = get_input('collections', []);
if (!empty($collection_settings)) {
	foreach ($collection_settings as $coollection_id => $preferred_methods) {
		if (!is_array($preferred_methods)) {
			$preferred_methods = [];
		}
		foreach ($preferred_methods as $preferred_method) {
			$collections_by_method[$preferred_method][] = $coollection_id;
		}
	}
}

foreach ($methods as $method) {
	$metaname = 'collections_notifications_preferences_' . $method;
	$user->$metaname = array_unique($collections_by_method[$method]);
}

system_message(elgg_echo('notifications:subscriptions:success'));
