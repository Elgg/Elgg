<?php
/**
 * New users admin widget
 */

$num_display = sanitize_int($vars['entity']->num_display, false);
// set default value for display number
if (!$num_display) {
	$num_display = 5;
}

echo elgg_list_entities(array(
	'type' => 'user',
	'subtype'=> null,
	'full_view' => false,
	'pagination' => false,
	'limit' => $num_display
));