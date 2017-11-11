<?php
/**
 * Banned users admin widget
 */
$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display ?: 4;

echo elgg_list_entities_from_metadata([
	'type' => 'user',
	'subtype'=> null,
	'metadata_name_value_pairs' => [
		['banned' => 'yes'],
	],
	'pagination' => false,
	'limit' => $num_display,
]);
