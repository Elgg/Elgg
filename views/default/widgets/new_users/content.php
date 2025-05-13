<?php
/**
 * New users admin widget
 */

$widget = elgg_extract('entity', $vars);
if (!$widget instanceof \ElggWidget) {
	return;
}

$num_display = (int) $widget->num_display ?: 4;

echo elgg_list_entities([
	'type' => 'user',
	'pagination' => false,
	'limit' => $num_display,
	'widget_more' => elgg_view_url($widget->getURL(), elgg_echo('more')),
]);
