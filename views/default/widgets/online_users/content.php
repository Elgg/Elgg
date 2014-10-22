<?php
/**
 * Online users widget
 */

$num_display = sanitize_int($vars['entity']->num_display, false);
// set default value for display number
if (!$num_display) {
	$num_display = 8;
}

echo get_online_users(array(
	'pagination' => false,
	'limit' => $num_display
));
