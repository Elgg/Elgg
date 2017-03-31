<?php

$listing_type = elgg_extract('type', $vars);
unset($vars['type']);

$target = elgg_extract('target', $vars);
unset($vars['target']);

$entity_type = elgg_extract('entity_type', $vars);
unset($vars['entity_type']);

$entity_subtype = elgg_extract('entity_subtype', $vars);
unset($vars['entity_subtype']);

if (!$entity_type || !$entity_subtype) {
	return;
}

$identifier = elgg_extract('identifier', $vars);
unset($vars['identifier']);

$options = [
	'types' => $entity_type,
	'subtypes' => $entity_subtype,
	'full_view' => false,
	'no_results' => elgg_echo("$identifier:no_results"),
	'preload_owners' => true,
	'preload_container' => true,
	'full_view' => false,
	'list_class' => "elgg-list-$entity_type-$entity_subtype",
];

switch ($listing_type) {
	case 'friends' :
		$options['relationship'] = 'friend';
		$options['relationship_guid'] = (int) $target->guid;
		$options['relationship_join_on'] = 'owner_guid';
		break;

	case 'owner' :
		$options['owner_guids'] = (int) $target->guid;
		$options['preload_owners'] = false;
		break;

	case 'group' :
		$options['container_guids'] = (int) $target->guid;
		$options['preload_containers'] = false;
		break;
}

$options = array_merge($options, $vars);

echo elgg_list_entities_from_relationship($options);