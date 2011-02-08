<?php

/**
 * Elgg notifications
 *
 * @package ElggNotifications
 */

$user = elgg_get_logged_in_user_entity();

global $NOTIFICATION_HANDLERS;
foreach($NOTIFICATION_HANDLERS as $method => $foo) {
	$subscriptions[$method] = get_input($method.'subscriptions');
	$personal[$method] = get_input($method.'personal');
	$collections[$method] = get_input($method.'collections');

	$metaname = 'collections_notifications_preferences_' . $method;
	$user->$metaname = $collections[$method];
	set_user_notification_setting($user->guid, $method, ($personal[$method] == '1') ? true : false);
	remove_entity_relationships($user->guid, 'notify' . $method, false, 'user');
}

// Add new ones
foreach($subscriptions as $key => $subscription) {
	if (is_array($subscription) && !empty($subscription)) {
		foreach($subscription as $subscriptionperson) {
			add_entity_relationship($user->guid, 'notify' . $key, $subscriptionperson);
		}
	}
}

system_message(elgg_echo('notifications:subscriptions:success'));

forward(REFERER);
