<?php
/**
 * List all the deleted entities of the given owner
 *
 * @uses $vars['options'] Additional listing options
 * @uses $vars['entity']  User
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

echo elgg_view('trash/listing/all', $vars);
