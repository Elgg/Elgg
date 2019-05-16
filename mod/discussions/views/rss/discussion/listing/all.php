<?php
/**
 * Renders a list of discussions, optionally filtered by container type
 *
 * Note: this view has a corresponding view in the default view type, changes should be reflected
 *
 * @uses $vars['container_type'] Container type filter to apply
 */

use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\QueryBuilder;

$options = [
	'type' => 'object',
	'subtype' => 'discussion',
	'order_by' => new OrderByClause('e.last_action', 'desc'),
	'limit' => max(20, elgg_get_config('default_limit')),
	'pagination' => false,
];

$container_type = elgg_extract('container_type', $vars);
if ($container_type) {
	$options['wheres'][] = function(QueryBuilder $qb, $main_alias) use ($container_type) {
		$c_join = $qb->joinEntitiesTable($main_alias, 'container_guid');
		
		return $qb->compare("{$c_join}.type", '=', $container_type, ELGG_VALUE_STRING);
	};
}

echo elgg_list_entities($options);
