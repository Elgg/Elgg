<?php
/**
 * Show a list of all group members
 */

$group = elgg_get_page_owner_entity();

elgg_push_entity_breadcrumbs($group);

if ($group->canEdit()) {
	elgg_register_menu_item('title', [
		'name' => 'groups:invite',
		'icon' => 'user-plus',
		'href' => elgg_generate_entity_url($group, 'invite'),
		'text' => elgg_echo('groups:invite'),
		'link_class' => 'elgg-button elgg-button-action',
	]);
}

echo elgg_view_page(elgg_echo('groups:members:title', [$group->getDisplayName()]), [
	'content' => elgg_list_relationships([
		'type' => 'user',
		'relationship' => 'member',
		'relationship_guid' => $group->guid,
		'inverse_relationship' => true,
		'sort_by' => get_input('sort_by', [
			'property' => 'name',
			'property_type' => 'metadata',
			'direction' => 'asc',
		]),
	]),
	'filter_id' => 'groups/members',
	'filter_value' => 'members',
	'filter_entity' => $group,
]);
