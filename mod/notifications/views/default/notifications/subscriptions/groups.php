<?php

use Elgg\Database\QueryBuilder;
use Elgg\Database\Clauses\JoinClause;

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

echo elgg_list_entities([
	'selects' => ['GROUP_CONCAT(ers.relationship) as relationships'],
	'types' => 'group',
	'joins' => [
		new JoinClause('entity_relationships', 'ers', function(QueryBuilder $qb, $joined_alias, $main_alias) use ($user) {
			return $qb->merge([
				$qb->compare("$joined_alias.guid_two", '=', "$main_alias.guid"),
				$qb->compare("$joined_alias.guid_one", '=', $user->guid, ELGG_VALUE_INTEGER),
			], 'AND');
		}),
	],
	'wheres' => [
		function(QueryBuilder $qb) {
			return $qb->merge([
				$qb->compare('ers.relationship', '=', 'member', ELGG_VALUE_STRING),
				$qb->compare('ers.relationship', 'LIKE', 'notify%', ELGG_VALUE_STRING),
			], 'OR');
		},
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
