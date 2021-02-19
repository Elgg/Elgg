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
];

$owner = $widget->getOwnerEntity();
if ($owner instanceof \ElggUser) {
	$options['owner_guid'] = $owner->guid;
} else {
	$options['container_guid'] = $widget->owner_guid;
}

$content = elgg_list_entities($options);
if (empty($content)) {
	echo elgg_echo('pages:none');
	return;
}

echo $content;

if ($owner instanceof \ElggGroup) {
	$url = elgg_generate_url('collection:object:page:group', ['guid' => $owner->guid]);
} else {
	$url = elgg_generate_url('collection:object:page:owner', ['username' => $owner->username]);
}

echo elgg_format_element('div', ['class' => 'elgg-widget-more'], elgg_view_url($url, elgg_echo('pages:more')));
