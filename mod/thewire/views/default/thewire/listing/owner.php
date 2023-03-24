<?php
/**
 * Show a listing of all Wire post for a given owner
 *
 * @user $vars['entity'] the owner entity
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

$vars['options'] = [
	'owner_guid' => $entity->guid,
	'preload_owners' => false,
];

echo elgg_view('thewire/listing/all', $vars);
