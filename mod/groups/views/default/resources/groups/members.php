<?php

use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\QueryBuilder;

$guid = (int) elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'group');

$group = get_entity($guid);

elgg_set_page_owner_guid($guid);

elgg_push_breadcrumb(elgg_echo('groups'), elgg_generate_url('collection:group:group:all'));
elgg_push_breadcrumb($group->getDisplayName(), $group->getURL());

if ($group->canEdit() && elgg_is_active_plugin('friends')) {
	elgg_register_menu_item('title', [
		'name' => 'groups:invite',
		'icon' => 'user-plus',
		'href' => elgg_generate_entity_url($group, 'invite'),
		'text' => elgg_echo('groups:invite'),
		'link_class' => 'elgg-button elgg-button-action',
	]);
}

// build page elements
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
		break;
	default:
		$options['joins'] = [
			new JoinClause('metadata', 'ens', function(QueryBuilder $qb, $join_alias, $main_alias) {
				$compare = [
					$qb->compare("{$join_alias}.entity_guid", '=', "{$main_alias}.guid_one"),
					$qb->compare("{$join_alias}.name", '=', 'name', ELGG_VALUE_STRING),
				];
				return $qb->merge($compare);
			}),
		];
		$options['order_by'] = [
			new OrderByClause('ens.value', 'ASC'),
		];
		
		break;
}

$title = elgg_echo('groups:members:title', [$group->getDisplayName()]);

// draw page
echo elgg_view_page($title, [
	'content' => elgg_list_relationships($options),
	'filter' => elgg_view_menu('groups_members', [
		'entity' => $group,
		'class' => 'elgg-tabs'
	]),
]);
