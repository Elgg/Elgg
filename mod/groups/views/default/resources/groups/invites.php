<?php
/**
 * Show a listing of all users who are invited to join this group
 */

$group = elgg_get_page_owner_entity();

elgg_push_breadcrumb(elgg_echo('groups'), elgg_generate_url('collection:group:group:all'));
elgg_push_breadcrumb($group->getDisplayName(), $group->getURL());

elgg_register_menu_item('title', [
	'name' => 'groups:invite',
	'icon' => 'user-plus',
	'href' => elgg_generate_entity_url($group, 'invite'),
	'text' => elgg_echo('groups:invite'),
	'link_class' => 'elgg-button elgg-button-action',
]);

$content = elgg_list_relationships([
	'relationship' => 'invited',
	'relationship_guid' => $group->guid,
	'no_results' => true,
]);

$tabs = elgg_view_menu('groups_members', [
	'entity' => $group,
	'class' => 'elgg-tabs'
]);

// draw page
echo elgg_view_page(elgg_echo('groups:invitedmembers'), [
	'content' => $content,
	'filter' => $tabs,
]);
