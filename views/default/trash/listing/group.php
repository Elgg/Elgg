<?php
/**
 * List all the deleted entities in the given group
 *
 * @uses $vars['options'] Additional listing options
 * @uses $vars['entity']  Group
 */

$options = (array) elgg_extract('options', $vars);
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggGroup) {
	return;
}

$owner_options = [
	'container_guid' => $entity->guid,
	'preload_containers' => false,
];

$vars['options'] = array_merge($options, $owner_options);

echo elgg_view('trash/listing/all', $vars);
