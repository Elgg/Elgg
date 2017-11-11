<?php
/**
 * Online users widget
 */

$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display ?: 8;

echo get_online_users([
	'pagination' => false,
	'limit' => $num_display,
]);
