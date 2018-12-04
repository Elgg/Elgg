<?php
/**
 * Group activity widget
 */

use Elgg\Activity\GroupRiverFilter;

$widget = elgg_extract('entity', $vars);

$num = (int) $widget->num_display ?: 8;
$guid = (int) $widget->group_guid;

$group = get_entity($guid);
if (!$group instanceof ElggGroup) {
	// no group selected yet
	echo '<p>' . elgg_echo('activity:widgets:group_activity:content:noselect') . '</p>';
	return;
}

echo elgg_list_river([
	'limit' => $num,
	'pagination' => false,
	'wheres' => [
		new GroupRiverFilter($group),
	],
	'no_results' => elgg_echo('activity:widgets:group_activity:content:noactivity'),
]);
