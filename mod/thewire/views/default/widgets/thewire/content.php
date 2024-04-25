<?php
/**
 * User wire post widget display view
 */

$widget = elgg_extract('entity', $vars);
if (!$widget instanceof \ElggWidget) {
	return;
}

$num_display = (int) $widget->num_display ?: 4;

$options = [
	'type' => 'object',
	'subtype' => 'thewire',
	'limit' => $num_display,
	'pagination' => false,
	'distinct' => false,
	'no_results' => elgg_echo('thewire:noposts'),
	'widget_more' => elgg_view_url($widget->getURL(), elgg_echo('thewire:moreposts')),
];

$owner = $widget->getOwnerEntity();
if ($owner instanceof \ElggUser) {
	$options['owner_guid'] = $owner->guid;
} elseif ($owner instanceof \ElggGroup) {
	$options['container_guid'] = $widget->owner_guid;
}

echo elgg_list_entities($options);
