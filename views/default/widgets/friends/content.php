<?php
/**
 * Friend widget display view
 */

$widget = elgg_extract('entity', $vars);

$owner = $widget->getOwnerEntity();
if (!($owner instanceof \ElggUser)) {
	return;
}

$num_display = sanitize_int($widget->num_display, false);
// set default value for display number
if (!$num_display) {
	$num_display = 12;
}

echo elgg_list_entities_from_relationship([
	'type' => 'user',
	'relationship' => 'friend',
	'relationship_guid' => $owner->guid,
	'limit' => $num_display,
	'size' => $widget->icon_size,
	'list_type' => 'gallery',
	'pagination' => false,
	'no_results' => elgg_echo('friends:none'),
]);
