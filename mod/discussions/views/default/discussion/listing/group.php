<?php
/**
 * List all group discussions
 *
 * Note: this view has a corresponding view in the rss view type, changes should be reflected
 *
 * @uses $vars['entity'] the group
 */

use Elgg\Database\Clauses\OrderByClause;

$entity = elgg_extract('entity', $vars);

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'discussion',
	'limit' => max(20, elgg_get_config('default_limit')),
	'order_by' => new OrderByClause('e.last_action', 'desc'),
	'container_guid' => (int) $entity->guid,
	'no_results' => elgg_echo('discussion:none'),
]);
