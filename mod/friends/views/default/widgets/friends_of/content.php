<?php
/**
 * Friends of widget display view
 */

$widget = elgg_extract('entity', $vars);
if (!$widget instanceof \ElggWidget) {
	return;
}

$owner = $widget->getOwnerEntity();
if (!$owner instanceof \ElggUser) {
	return;
}

$num_display = (int) $widget->num_display ?: 12;

echo elgg_list_entities([
	'type' => 'user',
	'relationship' => 'friend',
	'relationship_guid' => $owner->guid,
	'inverse_relationship' => true,
	'limit' => $num_display,
	'size' => $widget->icon_size ?: 'small',
	'list_type' => 'gallery',
	'pagination' => false,
	'no_results' => elgg_echo('friends:none'),
	'widget_more' => elgg_view_url($widget->getURL(), elgg_echo('more')),
]);
