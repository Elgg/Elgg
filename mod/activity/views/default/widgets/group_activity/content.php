<?php
/**
 * Group activity widget
 */

$widget = elgg_extract('entity', $vars);
if (!$widget instanceof \ElggWidget) {
	return;
}

$num = (int) $widget->num_display ?: 8;
$guid = (int) $widget->group_guid;

$group = get_entity($guid);
if (!$group instanceof ElggGroup) {
	// no group selected yet
	echo elgg_view('page/components/no_results', [
		'no_results' => elgg_echo('widgets:group_activity:content:noselect'),
	]);
	return;
}

echo elgg_view('river/listing/group', [
	'entity' => $group,
	'options' => [
		'limit' => $num,
		'pagination' => false,
		'no_results' => elgg_echo('widgets:group_activity:content:noactivity'),
		'widget_more' => elgg_view_url($widget->getURL(), elgg_echo('activity:more')),
	],
]);
