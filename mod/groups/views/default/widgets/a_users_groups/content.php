<?php
/**
 * Group membership widget
 */

$widget = elgg_extract('entity', $vars);
if (!$widget instanceof \ElggWidget) {
	return;
}

$num_display = (int) $widget->num_display ?: 4;

echo elgg_list_entities([
	'type' => 'group',
	'relationship' => 'member',
	'relationship_guid' => $widget->owner_guid,
	'limit' => $num_display,
	'pagination' => false,
	'no_results' => elgg_echo('groups:none'),
	'widget_more' => elgg_view_url($widget->getURL(), elgg_echo('groups:more')),
]);
