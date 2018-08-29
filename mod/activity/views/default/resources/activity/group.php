<?php

use Elgg\Database\QueryBuilder;
use Elgg\Database\Clauses\JoinClause;

$group = elgg_get_page_owner_entity();

elgg_entity_gatekeeper($group->guid, 'group');

elgg_group_tool_gatekeeper('activity');

$title = elgg_echo('collection:river:group');

elgg_push_breadcrumb(elgg_echo('groups'), "groups/all");
elgg_push_breadcrumb($group->getDisplayName(), $group->getURL());

$on_object = function (QueryBuilder $qb, $joined_alias, $main_alias) {
	return $qb->compare("{$joined_alias}.guid", '=', "{$main_alias}.object_guid");
};
$on_target = function (QueryBuilder $qb, $joined_alias, $main_alias) {
	return $qb->compare("{$joined_alias}.guid", '=', "{$main_alias}.target_guid");
};

$options = [
	'joins' => [
		new JoinClause('entities', 'e1', $on_object),
		new JoinClause('entities', 'e2', $on_target, 'left'),
	],
	'wheres' => [
		function (QueryBuilder $qb, $main_alias) use ($group) {
			$wheres = [];
			$wheres[] = $qb->compare("{$main_alias}.object_guid", '=', $group->guid);
			$wheres[] = $qb->compare('e1.container_guid', '=', $group->guid);
			$wheres[] = $qb->compare('e2.container_guid', '=', $group->guid);
			
			return $qb->merge($wheres, 'OR');
		},
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
