<?php
/**
 * The wire sidebar
 */

$entity = elgg_extract('entity', $vars, elgg_get_page_owner_entity());

echo elgg_view('page/elements/tagcloud_block', [
	'subtypes' => 'thewire',
	'container_guid' => $entity?->guid,
]);
