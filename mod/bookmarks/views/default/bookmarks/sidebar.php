<?php
/**
 * Bookmarks sidebar
 */

$entity = elgg_extract('entity', $vars, elgg_get_page_owner_entity());

echo elgg_view('page/elements/comments_block', [
	'subtypes' => 'bookmarks',
	'container_guid' => $entity ? $entity->guid : null,
]);

echo elgg_view('page/elements/tagcloud_block', [
	'subtypes' => 'bookmarks',
	'container_guid' => $entity ? $entity->guid : null,
]);
