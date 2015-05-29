<?php

$guid = get_input('guid');
elgg_entity_gatekeeper($guid, 'group');

$group = get_entity($guid);

elgg_set_page_owner_guid($guid);

elgg_group_gatekeeper();

$title = elgg_echo('groups:members:title', array($group->name));

elgg_push_breadcrumb($group->name, $group->getURL());
elgg_push_breadcrumb(elgg_echo('groups:members'));

$db_prefix = elgg_get_config('dbprefix');
$content = elgg_list_entities_from_relationship(array(
	'relationship' => 'member',
	'relationship_guid' => $group->guid,
	'inverse_relationship' => true,
	'type' => 'user',
	'limit' => (int)get_input('limit', max(20, elgg_get_config('default_limit')), false),
	'joins' => array("JOIN {$db_prefix}users_entity u ON e.guid=u.guid"),
	'order_by' => 'u.name ASC',
));

$params = array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
