<?php
/**
 * Saves subsription notification settings by group
 *
 * @uses $vars['user'] ElggUser
 */

use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\QueryBuilder;

$user = elgg_extract('user', $vars);
if (!$user instanceof ElggUser) {
	return;
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $user->guid,
]);

echo elgg_view('output/longtext', [
	'value' => elgg_echo('notifications:subscriptions:groups:description'),
]);

// Returns a list of groups a user a member of, as well as any other groups
// the user is subscribed to
$list = elgg_list_entities([
	'selects' => ['GROUP_CONCAT(ers.relationship) as relationships'],
	'types' => 'group',
	'joins' => [
		new JoinClause('entity_relationships', 'ers', function(QueryBuilder $qb, $joined_alias, $main_alias) use ($user) {
			return $qb->merge([
				$qb->compare("{$joined_alias}.guid_two", '=', "{$main_alias}.guid"),
				$qb->compare("{$joined_alias}.guid_one", '=', $user->guid, ELGG_VALUE_INTEGER),
			], 'AND');
		}),
	],
	'wheres' => [
		function(QueryBuilder $qb) {
			return $qb->merge([
				$qb->compare('ers.relationship', '=', 'member', ELGG_VALUE_STRING),
				$qb->compare('ers.relationship', 'LIKE', 'notify:%', ELGG_VALUE_STRING),
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
	'limit' => max(20, elgg_get_config('default_limit')),
	'list_class' => 'elgg-subscriptions',
	'item_class' => 'elgg-subscription-record',
]);

if (empty($list)) {
	echo elgg_view('page/components/no_results', [
		'no_results' => elgg_echo('notifications:subscriptions:no_results'),
	]);
	return;
}

echo $list;

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
