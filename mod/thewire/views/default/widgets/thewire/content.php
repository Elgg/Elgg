<?php
/**
 * User wire post widget display view
 */

/* @var $widget \ElggWidget */
$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display ?: 4;

$options = [
	'type' => 'object',
	'subtype' => 'thewire',
	'limit' => $num_display,
	'pagination' => false,
	'distinct' => false,
	'no_results' => elgg_echo('thewire:noposts'),
];

$owner = $widget->getOwnerEntity();
if ($owner instanceof \ElggUser) {
	$options['owner_guid'] = $owner->guid;
	$options['widget_more'] = elgg_view_url(elgg_generate_url('collection:object:thewire:owner', ['username' => $owner->username]), elgg_echo('thewire:moreposts'));
} elseif ($owner instanceof \ElggGroup) {
	$options['container_guid'] = $widget->owner_guid;
} else {
	$options['widget_more'] = elgg_view_url(elgg_generate_url('collection:object:thewire:all'), elgg_echo('thewire:moreposts'));
}

echo elgg_list_entities($options);
