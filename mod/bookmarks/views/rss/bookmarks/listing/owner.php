<?php
/**
 * Display user's bookmarks
 *
 * Note: this view has a corresponding view in the default view type, changes should be reflected
 *
 * @uses $vars['options'] Additional listing options
 * @uses $vars['entity']  The user to list content for
 */

$options = (array) elgg_extract('options', $vars);
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggUser) {
	return;
}

$owner_options = [
	'owner_guid' => $entity->guid,
	'preload_owners' => false,
];

$vars['options'] = array_merge($options, $owner_options);

echo elgg_view('bookmarks/listing/all', $vars);
