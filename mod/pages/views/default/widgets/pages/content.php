<?php
/**
 * Pages widget
 */

/* @var $widget \ElggWidget */
$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->pages_num ?: 4;

$options = [
	'type' => 'object',
	'subtype' => 'page',
	'metadata_name_value_pairs' => [
		'parent_guid' => 0,
	],
	'limit' => $num_display,
	'pagination' => false,
	'distinct' => false,
	'no_results' => elgg_echo('pages:none'),
];

$owner = $widget->getOwnerEntity();
if ($owner instanceof \ElggUser) {
	$options['owner_guid'] = $owner->guid;
	$url = elgg_generate_url('collection:object:page:owner', ['username' => $owner->username]);
} elseif ($owner instanceof \ElggGroup) {
	$options['container_guid'] = $widget->owner_guid;
	$url = elgg_generate_url('collection:object:page:group', ['guid' => $owner->guid]);
} else {
	$url = elgg_generate_url('collection:object:page:all');
}

$options['widget_more'] = elgg_view_url($url, elgg_echo('pages:more'));

echo elgg_list_entities($options);
