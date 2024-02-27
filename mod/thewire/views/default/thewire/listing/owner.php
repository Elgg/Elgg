<?php
/**
 * Show a listing of all Wire post for a given owner
 *
 * @uses $vars['options'] Additional listing options
 * @uses $vars['entity'] the owner entity
 */

$options = (array) elgg_extract('options', $vars);
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

$owner_options = [
	'owner_guid' => $entity->guid,
	'preload_owners' => false,
];

$vars['options'] = array_merge($options, $owner_options);

echo elgg_view('thewire/listing/all', $vars);
