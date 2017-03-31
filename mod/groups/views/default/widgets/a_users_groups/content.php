<?php

/**
 * Elgg file widget view
 */
$widget = elgg_extract('entity', $vars);

echo elgg_list_entities_from_relationship([
	'type' => 'group',
	'relationship' => 'member',
	'relationship_guid' => $widget->owner_guid,
	'limit' => $widget->num_display,
	'pagination' => elgg_view('navigation/more', [
		'#class' => 'elgg-widgets-more',
		'href' => 'groups/member/' . $widget->getOwnerEntity()->username,
		'text' => elgg_echo('groups:more'),
		'is_trusted' => true,
	]),
	'no_results' => elgg_echo('groups:none'),
	'list_class' => 'list-group-flush',
]);
