<?php
/**
 * File sidebar
 */

echo elgg_view('page/elements/comments_block', [
	'subtypes' => 'file',
	'container_guid' => elgg_get_page_owner_guid(),
]);

echo elgg_view('page/elements/tagcloud_block', [
	'subtypes' => 'file',
	'container_guid' => elgg_get_page_owner_guid(),
]);
