<?php
/**
 * List all files
 *
 * Note: this view has a corresponding view in the default view type, changes should be reflected
 */

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'file',
	'distinct' => false,
	'pagination' => false,
]);
