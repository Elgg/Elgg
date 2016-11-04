<?php
/**
 * Group activity widget
 */

$widget = elgg_extract('entity', $vars);

$num = (int) $widget->num_display;
$guid = (int) $widget->group_guid;

if (empty($guid)) {
	// no group selected yet
	echo '<p>' . elgg_echo('groups:widget:group_activity:content:noselect') . '</p>';
	return;
}

$db_prefix = elgg_get_config('dbprefix');
echo elgg_list_river([
	'limit' => $num,
	'pagination' => false,
	'joins' => ["JOIN {$db_prefix}entities e1 ON e1.guid = rv.object_guid"],
	'wheres' => ["(e1.container_guid = $guid)"],
	'no_results' => elgg_echo('groups:widget:group_activity:content:noactivity'),
]);
