<?php
/**
 * Tag cloud widget
 */

$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_items ?: 30;

elgg_push_context('tags');
echo elgg_view_tagcloud([
	'owner_guid' => $widget->owner_guid,
	'threshold' => 1,
	'limit' => $num_display,
	'tag_name' => 'tags',
]);
elgg_pop_context();
