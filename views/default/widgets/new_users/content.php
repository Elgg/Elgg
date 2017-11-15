<?php
/**
 * New users admin widget
 */

$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display ?: 4;

echo elgg_list_entities([
	'type' => 'user',
	'subtype'=> null,
	'full_view' => false,
	'pagination' => false,
	'limit' => $num_display,
]);
