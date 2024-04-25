<?php
/**
 * Elgg file widget view
 */

$widget = elgg_extract('entity', $vars);
if (!$widget instanceof \ElggWidget) {
	return;
}

$num_display = (int) $widget->num_display ?: 4;

$options = [
	'type' => 'object',
	'subtype' => 'file',
	'limit' => $num_display,
	'pagination' => false,
	'distinct' => false,
	'no_results' => elgg_echo('file:none'),
	'widget_more' => elgg_view_url($widget->getURL(), elgg_echo('file:more')),
];

$owner = $widget->getOwnerEntity();
if ($owner instanceof \ElggUser) {
	$options['owner_guid'] = $owner->guid;
} elseif ($owner instanceof \ElggGroup) {
	$options['container_guid'] = $widget->owner_guid;
}

echo elgg_list_entities($options);
