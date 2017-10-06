<?php
/**
 * Group subscription preferences
 *
 * @uses $vars['user'] Subsriber
 */
$user = elgg_extract('user', $vars);
if (!$user instanceof ElggUser) {
	return;
}

// Returns a list of groups a user a member of, as well as any other groups
// the user is subscribed to
$dbprefix = elgg_get_config('dbprefix');

echo elgg_list_entities_from_metadata([
	'selects' => ['GROUP_CONCAT(ers.relationship) as relationships'],
	'types' => 'group',
	'joins' => [
		"JOIN {$dbprefix}entity_relationships ers
			ON e.guid = ers.guid_two AND ers.guid_one = $user->guid",
	],
	'wheres' => [
		"ers.relationship = 'member' OR ers.relationship LIKE 'notify%'"
	],
	'group_by' => 'e.guid',
	'order_by_metadata' => [
		'name' => 'name',
		'direction' => 'ASC',
	],
	'offset_key' => 'subscriptions_groups',
	'item_view' => 'notifications/subscriptions/record',
	'user' => $user,
	'no_results' => elgg_echo('notifications:subscriptions:no_results'),
	'limit' => max(20, elgg_get_config('default_limit')),
	'list_class' => 'elgg-subscriptions',
	'item_class' => 'elgg-subscription-record',
]);
