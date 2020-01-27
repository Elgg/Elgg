<?php

use Elgg\Activity\GroupRiverFilter;
use Elgg\Database\QueryBuilder;

$group_guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($group_guid, 'group');

/* @var $group ElggGroup */
$group = get_entity($group_guid);

elgg_set_page_owner_guid($group->guid);

elgg_group_tool_gatekeeper('activity');

elgg_push_breadcrumb(elgg_echo('groups'), elgg_generate_url('collection:group:group:all'));
elgg_push_breadcrumb($group->getDisplayName(), $group->getURL());

$options = [
	'wheres' => [
		new GroupRiverFilter($group),
	],
	'no_results' => elgg_echo('river:none'),
];

$type = preg_replace('[\W]', '', get_input('type', 'all'));
$subtype = preg_replace('[\W]', '', get_input('subtype', ''));
if ($subtype) {
	$selector = "type=$type&subtype=$subtype";
} else {
	$selector = "type=$type";
}

if ($type != 'all') {
	$options['type'] = $type;
	if ($subtype) {
		$options['subtype'] = $subtype;
	}
}

$content = elgg_view('river/filter', ['selector' => $selector]);
$content .= elgg_list_river($options);

echo elgg_view_page(elgg_echo('collection:river:group'), [
	'content' => $content,
	'class' => 'elgg-river-layout',
]);
