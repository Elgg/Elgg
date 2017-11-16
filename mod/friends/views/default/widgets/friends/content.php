<?php
/**
 * Friend widget display view
 */

$widget = elgg_extract('entity', $vars);

$owner = $widget->getOwnerEntity();
if (!($owner instanceof \ElggUser)) {
	return;
}

$num_display = (int) $widget->num_display ?: 12;

echo elgg_list_entities([
	'type' => 'user',
	'relationship' => 'friend',
	'relationship_guid' => $owner->guid,
	'limit' => $num_display,
	'size' => $widget->icon_size ?: 'small',
	'list_type' => 'gallery',
	'pagination' => false,
	'no_results' => elgg_echo('friends:none'),
]);
