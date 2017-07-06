<?php

/**
 * Friends preferences
 *
 * @uses $vars['user'] Subscriber
 */
$user = elgg_extract('user', $vars);
if (!$user instanceof ElggUser) {
	return;
}

// Returns a list of all friends, as well as anyone else who the user is subscribed to
$dbprefix = elgg_get_config('dbprefix');
$options = [
	'selects' => ['GROUP_CONCAT(ers.relationship) as relationships'],
	'types' => 'user',
	'joins' => [
		"JOIN {$dbprefix}users_entity ue ON ue.guid = e.guid",
		"JOIN {$dbprefix}entity_relationships ers 
			ON e.guid = ers.guid_two AND ers.guid_one = $user->guid",
	],
	'wheres' => [
		"ers.relationship = 'friend' OR ers.relationship LIKE 'notify%'"
	],
	'order_by' => 'ue.name',
	'group_by' => 'e.guid',
	'offset_key' => 'subscriptions_users',
	'item_view' => 'notifications/subscriptions/record',
	'user' => $user,
	'no_results' => elgg_echo('notifications:subscriptions:no_results'),
	'limit' => max(20, elgg_get_config('default_limit')),
];

echo elgg_list_entities($options);
