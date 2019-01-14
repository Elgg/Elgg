<?php
/**
 * Group activity widget
 */

use Elgg\Activity\GroupRiverFilter;
use Elgg\Database\QueryBuilder;

$widget = elgg_extract('entity', $vars);

$num = (int) $widget->num_display ?: 8;
$guid = (int) $widget->group_guid;

$group = get_entity($guid);
if (!$group instanceof ElggGroup) {
	// no group selected yet
	echo '<p>' . elgg_echo('widgets:group_activity:content:noselect') . '</p>';
	return;
}

echo elgg_list_river([
	'limit' => $num,
	'pagination' => false,
	'wheres' => [
		function (QueryBuilder $qb, $main_alias) use ($group) {
			$group = new GroupRiverFilter($group);
			
			return $group($qb, $main_alias);
		},
	],
	'no_results' => elgg_echo('widgets:group_activity:content:noactivity'),
]);
