<?php

/**
 * Elgg notifications group save
 *
 * @package ElggNotifications
 */

// Load important global vars
global $NOTIFICATION_HANDLERS;

// Get group memberships and condense them down to an array of guids
$groups = array();
if ($groupmemberships = elgg_get_entities_from_relationship(array('relationship' => 'member', 'relationship_guid' => get_loggedin_userid(), 'types' => 'group', 'limit' => 9999))) {
	foreach($groupmemberships as $groupmembership) {
		$groups[] = $groupmembership->guid;
	}
}		

foreach($NOTIFICATION_HANDLERS as $method => $foo) {
	$subscriptions[$method] = get_input($method.'subscriptions');
	$personal[$method] = get_input($method.'personal');
	$collections[$method] = get_input($method.'collections');
	if (!empty($groups)) {
		foreach($groups as $group) {
			if (in_array($group,$subscriptions[$method])) {
				add_entity_relationship(get_loggedin_userid(), 'notify'.$method, $group);
			} else {
				remove_entity_relationship(get_loggedin_userid(), 'notify'.$method, $group);
			}
		}
	}
}

system_message(elgg_echo('notifications:subscriptions:success'));

forward($_SERVER['HTTP_REFERER']);
