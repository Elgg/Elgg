<?php
/**
 * New users admin widget
 */

$widget = elgg_extract('entity', $vars);
if (!$widget instanceof ElggWidget) {
	return;
}

$num_display = (int) $widget->num_display ?: 4;

echo elgg_list_entities([
	'type' => 'user',
	'subtype' => null,
	'full_view' => false,
	'pagination' => false,
	'limit' => $num_display,
	'widget_more' => elgg_view_url('admin/users', elgg_echo('more')),
]);
