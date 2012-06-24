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

global $NOTIFICATION_HANDLERS;
$subscriptions = array();
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
