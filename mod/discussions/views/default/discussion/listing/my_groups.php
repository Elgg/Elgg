<?php
/**
 * List all discussions in the users groups
 *
 * Note: this view has a corresponding view in the rss view type, changes should be reflected
 *
 * @uses $vars['entity'] the user
 */

use Elgg\Database\Clauses\OrderByClause;

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggUser) {
	return;
}

// check of the user is a member of any groups
$user_groups = $entity->getGroups([
	'limit' => false,
	'callback' => function ($row) {
		return (int) $row->guid;
	},
]);

if (empty($user_groups)) {
	echo elgg_view('page/components/no_results', [
		'no_results' => elgg_echo('discussion:error:no_groups'),
	]);
	return;
}

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'discussion',
	'container_guids' => $user_groups,
	'limit' => max(20, elgg_get_config('default_limit')),
	'order_by' => new OrderByClause('e.last_action', 'desc'),
	'no_results' => elgg_echo('discussion:none'),
]);
