<?php
/**
 * File sidebar
 */

$entity = elgg_extract('entity', $vars, elgg_get_page_owner_entity());

echo elgg_view('page/elements/comments_block', [
	'subtypes' => 'file',
	'container_guid' => $entity?->guid,
]);

echo elgg_view('page/elements/tagcloud_block', [
	'subtypes' => 'file',
	'container_guid' => $entity?->guid,
]);
