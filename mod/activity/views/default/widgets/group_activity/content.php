<?php
/**
 * Group activity widget
 */

/* @var $widget \ElggWidget */
$widget = elgg_extract('entity', $vars);

$num = (int) $widget->num_display ?: 8;
$guid = (int) $widget->group_guid;

$group = get_entity($guid);
if (!$group instanceof ElggGroup) {
	// no group selected yet
	echo '<p>' . elgg_echo('widgets:group_activity:content:noselect') . '</p>';
	return;
}

echo elgg_view('river/listing/group', [
	'entity' => $group,
	'options' => [
		'limit' => $num,
		'pagination' => false,
		'no_results' => elgg_echo('widgets:group_activity:content:noactivity'),
	],
]);
