<?php

/**
 * Elgg notifications group save
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

// Get group memberships and condense them down to an array of guids
$groups = array();
$options = array(
	'relationship' => 'member',
	'relationship_guid' => $user->guid,
	'type' => 'group',
	'limit' => false,
);
if ($groupmemberships = elgg_get_entities_from_relationship($options)) {
	foreach ($groupmemberships as $groupmembership) {
		$groups[] = $groupmembership->guid;
	}
}

if (!empty($groups)) {
	$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();
	foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
		$subscriptions[$method] = get_input($method.'subscriptions', array());
		foreach ($groups as $group) {
			if (in_array($group, $subscriptions[$method])) {
				elgg_add_subscription($user->guid, $method, $group);
			} else {
				elgg_remove_subscription($user->guid, $method, $group);
			}
		}
	}
}

system_message(elgg_echo('notifications:subscriptions:success'));

forward(REFERER);
