<?php
/**
 * Display bookmarks listing
 *
 * Note: this view has a corresponding view in the default view type, changes should be reflected
 */

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'bookmarks',
	'distinct' => false,
	'pagination' => false,
]);
