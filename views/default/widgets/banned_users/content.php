<?php
/**
 * Banned users admin widget
 */
$widget = elgg_extract('entity', $vars);

$num_display = sanitize_int($widget->num_display, false);
// set default value for display number
if (!$num_display) {
	$num_display = 5;
}

echo elgg_list_entities_from_metadata([
	'type' => 'user',
	'subtype'=> null,
	'metadata_name_value_pairs' => [
		['banned' => 'yes'],
	],
	'pagination' => false,
	'limit' => $num_display,
]);
