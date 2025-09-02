<?php

$page_owner = elgg_get_page_owner_entity();

if ($page_owner->guid === elgg_get_logged_in_user_guid()) {
	$title = elgg_echo('groups:yours');
} else {
	$title = elgg_echo('groups:user', [$page_owner->getDisplayName()]);
}

elgg_push_collection_breadcrumbs('group', 'group');

if (elgg_get_plugin_setting('limited_groups', 'groups') !== 'yes' || elgg_is_admin_logged_in()) {
	elgg_register_title_button('add', 'group', 'group');
}

$content = elgg_list_entities([
	'type' => 'group',
	'relationship' => 'member',
	'relationship_guid' => elgg_get_page_owner_guid(),
	'inverse_relationship' => false,
	'sort_by' => [
		'property' => 'name',
		'direction' => 'ASC',
	],
	'no_results' => elgg_echo('groups:none'),
]);

echo elgg_view_page($title, [
	'content' => $content,
	'filter_id' => 'groups/member',
	'filter_value' => 'member',
]);
