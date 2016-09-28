<?php

/**
 * Saves subscription record notification settings
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

$subscriptions = (array) get_input('subscriptions', []);
foreach ($subscriptions as $target_guid => $preferred_methods) {
	if (!is_array($preferred_methods)) {
		$preferred_methods = [];
	}
	foreach ($methods as $method) {
		if (in_array($method, $preferred_methods)) {
			elgg_add_subscription($user->guid, $method, $target_guid);
		} else {
			elgg_remove_subscription($user->guid, $method, $target_guid);
		}
	}
}

system_message(elgg_echo('notifications:subscriptions:success'));
