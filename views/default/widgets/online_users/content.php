<?php
/**
 * Online users widget
 */

$widget = elgg_extract('entity', $vars);

$num_display = sanitize_int($widget->num_display, false);
// set default value for display number
if (!$num_display) {
	$num_display = 8;
}

echo get_online_users([
	'pagination' => false,
	'limit' => $num_display,
]);
