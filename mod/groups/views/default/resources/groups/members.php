<?php

use Elgg\Database\Clauses\OrderByClause;

$guid = (int) elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'group');

$group = get_entity($guid);

elgg_set_page_owner_guid($guid);

elgg_push_breadcrumb(elgg_echo('groups'), "groups/all");
elgg_push_breadcrumb($group->getDisplayName(), $group->getURL());

$options = [
	'relationship' => 'member',
	'relationship_guid' => $group->guid,
	'inverse_relationship' => true,
	'type' => 'user',
	'limit' => (int) get_input('limit', max(20, elgg_get_config('default_limit')), false),
];

$sort = elgg_extract('sort', $vars);
switch ($sort) {
	case 'newest':
		$options['order_by'] = [
			new OrderByClause('r.time_created', 'DESC'),
		];
		break;
	default:
		$options['order_by_metadata'] = [
			'name' => 'name',
			'direction' => 'ASC',
		];
		break;
}

$title = elgg_echo('groups:members:title', [$group->getDisplayName()]);

$tabs = elgg_view_menu('groups_members', [
	'entity' => $group,
	'class' => 'elgg-tabs'
]);

$content = elgg_list_entities($options);

$params = [
	'content' => $content,
	'title' => $title,
	'filter' => $tabs,
];
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
