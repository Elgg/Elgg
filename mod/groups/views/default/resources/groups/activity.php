<?php

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'group');

elgg_set_page_owner_guid($guid);

elgg_group_gatekeeper();

$group = get_entity($guid);

$title = elgg_echo('groups:activity');

elgg_push_breadcrumb($group->name, $group->getURL());
elgg_push_breadcrumb($title);

$db_prefix = elgg_get_config('dbprefix');

$content = elgg_list_river(array(
	'joins' => array(
		"JOIN {$db_prefix}entities e1 ON e1.guid = rv.object_guid",
		"LEFT JOIN {$db_prefix}entities e2 ON e2.guid = rv.target_guid",
	),
	'wheres' => array(
		"(e1.container_guid = $group->guid OR e2.container_guid = $group->guid)",
	),
	'no_results' => elgg_echo('groups:activity:none'),
));

$params = array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'entity' => $group,
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);