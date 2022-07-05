<?php
/**
 * Elgg file widget view
 */

/* @var $widget \ElggWidget */
$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display ?: 4;

$options = [
	'type' => 'object',
	'subtype' => 'file',
	'limit' => $num_display,
	'pagination' => false,
	'distinct' => false,
	'no_results' => elgg_echo('file:none'),
];

$owner = $widget->getOwnerEntity();
if ($owner instanceof \ElggUser) {
	$options['owner_guid'] = $owner->guid;
	$url = elgg_generate_url('collection:object:file:owner', ['username' => $owner->username]);
} elseif ($owner instanceof \ElggGroup) {
	$options['container_guid'] = $widget->owner_guid;
	$url = elgg_generate_url('collection:object:file:group', ['guid' => $owner->guid]);
} else {
	$url = elgg_generate_url('collection:object:file:all');
}

$options['widget_more'] = elgg_view_url($url, elgg_echo('file:more'));

echo elgg_list_entities($options);
