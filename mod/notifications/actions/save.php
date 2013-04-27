<?php

/**
 * Elgg notifications
 *
 * @package ElggNotifications
 */

$current_user = elgg_get_logged_in_user_entity();

$guid = (int) get_input('guid', 0);
if (!$guid || !($user = get_entity($guid))) {
	forward();
}
if (($user->guid != $current_user->guid) && !$current_user->isAdmin()) {
	forward();
}

$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();
$subscriptions = array();
foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
	$personal[$method] = get_input($method.'personal');
	set_user_notification_setting($user->guid, $method, ($personal[$method] == '1') ? true : false);

	$collections[$method] = get_input($method.'collections');
	$metaname = 'collections_notifications_preferences_' . $method;
	$user->$metaname = $collections[$method];

	$subscriptions[$method] = get_input($method.'subscriptions');
	remove_entity_relationships($user->guid, 'notify' . $method, false, 'user');
}

// Add new ones
foreach ($subscriptions as $method => $subscription) {
	if (is_array($subscription) && !empty($subscription)) {
		foreach ($subscription as $subscriptionperson) {
			elgg_add_subscription($user->guid, $method, $subscriptionperson);
		}
	}
}

system_message(elgg_echo('notifications:subscriptions:success'));

forward(REFERER);
