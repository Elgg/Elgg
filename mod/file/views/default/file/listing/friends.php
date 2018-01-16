<?php

$entity = elgg_extract('entity', $vars);

file_register_toggle();

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'file',
	'full_view' => false,
	'relationship' => 'friend',
	'relationship_guid' => $entity->guid,
	'relationship_join_on' => 'owner_guid',
	'no_results' => elgg_echo("file:none"),
	'preload_owners' => true,
	'preload_containers' => true,
]);
