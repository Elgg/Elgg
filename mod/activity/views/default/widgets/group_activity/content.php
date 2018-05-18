<?php
use Elgg\Database\QueryBuilder;
use Elgg\Database\Clauses\JoinClause;

/**
 * Group activity widget
 */

$widget = elgg_extract('entity', $vars);

$num = (int) $widget->num_display ?: 8;
$guid = (int) $widget->group_guid;

if (empty($guid)) {
	// no group selected yet
	echo '<p>' . elgg_echo('activity:widgets:group_activity:content:noselect') . '</p>';
	return;
}

echo elgg_list_river([
	'limit' => $num,
	'pagination' => false,
	'joins' => [
		new JoinClause('entities', 'e1', function(QueryBuilder $qb, $joined_alias, $main_alias) {
			return $qb->compare("$joined_alias.guid", '=', "$main_alias.object_guid");
		}),
	],
	'wheres' => [
		function(QueryBuilder $qb) use ($guid) {
			return $qb->compare('e1.container_guid', '=', $guid, ELGG_VALUE_INTEGER);
		}
	],
	'no_results' => elgg_echo('activity:widgets:group_activity:content:noactivity'),
]);
