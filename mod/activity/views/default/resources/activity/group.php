<?php

elgg_group_gatekeeper();

$group = elgg_get_page_owner_entity();

if (!$group instanceof \ElggGroup) {
	throw new \Elgg\EntityNotFoundException();
}

elgg_group_tool_gatekeeper('activity');

$title = elgg_echo('collection:river:group');

elgg_push_breadcrumb(elgg_echo('groups'), "groups/all");
elgg_push_breadcrumb($group->getDisplayName(), $group->getURL());

$db_prefix = elgg_get_config('dbprefix');

$options = [
	'joins' => [
		"JOIN {$db_prefix}entities e1 ON e1.guid = rv.object_guid",
		"LEFT JOIN {$db_prefix}entities e2 ON e2.guid = rv.target_guid",
	],
	'wheres' => [
		"(e1.container_guid = $group->guid OR e2.container_guid = $group->guid)",
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

$content = elgg_view('core/river/filter', ['selector' => $selector]);
$content .= elgg_list_river($options);

$body = elgg_view_layout('content', [
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'class' => 'elgg-river-layout',
]);

echo elgg_view_page($title, $body);
