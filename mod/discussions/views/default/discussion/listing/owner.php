<?php
/**
 * List all user discussions
 *
 * Note: this view has a corresponding view in the rss view type, changes should be reflected
 *
 * @uses $vars['entity'] the user
 */

use Elgg\Database\Clauses\OrderByClause;

$entity = elgg_extract('entity', $vars);

$options = [
	'type' => 'object',
	'subtype' => 'discussion',
	'limit' => max(20, elgg_get_config('default_limit')),
	'order_by' => new OrderByClause('e.last_action', 'desc'),
	'no_results' => elgg_echo('discussion:none'),
];

if ($entity instanceof ElggUser) {
	// Display all discussions started by the user regardless of
	// the entity that is working as a container. See #4878.
	$options['owner_guid'] = (int) $entity->guid;
} else {
	$options['container_guid'] = (int) $entity->guid;
}

echo elgg_list_entities($options);
