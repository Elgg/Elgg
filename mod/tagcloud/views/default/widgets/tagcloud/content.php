<?php
/**
 * Tag cloud widget
 */

$widget = elgg_extract('entity', $vars);

elgg_push_context('tags');
echo elgg_view_tagcloud([
	'owner_guid' => $widget->owner_guid,
	'threshold' => 1,
	'limit' => $widget->num_items,
	'tag_name' => 'tags',
]);
elgg_pop_context();
