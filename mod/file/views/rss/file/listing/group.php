<?php
/**
 * List all group files
 *
 * Note: this view has a corresponding view in the default view type, changes should be reflected
 *
 * @uses $vars['options'] Additional listing options
 * @uses $vars['entity']  Group to list content for
 */

$options = (array) elgg_extract('options', $vars);
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggGroup) {
	return;
}

$group_options = [
	'container_guid' => $entity->guid,
	'preload_containers' => false,
];

$vars['options'] = array_merge($options, $group_options);

echo elgg_view('file/listing/all', $vars);
