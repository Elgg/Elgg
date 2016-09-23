<?php
/**
 * New users admin widget
 */

$widget = elgg_extract('entity', $vars);

$num_display = sanitize_int($widget->num_display, false);
// set default value for display number
if (!$num_display) {
	$num_display = 5;
}

echo elgg_list_entities([
	'type' => 'user',
	'subtype'=> null,
	'full_view' => false,
	'pagination' => false,
	'limit' => $num_display,
]);