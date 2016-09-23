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


// backward compatibility when we couldn't set widget title (pre 1.9)
if (!$widget->title) {
	$title = get_entity($guid)->name;
	echo "<h3>$title</h3>";
}

$db_prefix = elgg_get_config('dbprefix');
$activity = elgg_list_river([
	'limit' => $num,
	'pagination' => false,
	'joins' => ["JOIN {$db_prefix}entities e1 ON e1.guid = rv.object_guid"],
	'wheres' => ["(e1.container_guid = $guid)"],
]);

if (empty($activity)) {
	echo '<p>' . elgg_echo('groups:widget:group_activity:content:noactivity') . '</p>';
	return;
}

echo $activity;
