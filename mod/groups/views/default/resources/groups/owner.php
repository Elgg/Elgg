<?php

$page_owner = elgg_get_page_owner_entity();

if ($page_owner->guid == elgg_get_logged_in_user_guid()) {
	$title = elgg_echo('groups:owned');
} else {
	$title = elgg_echo('groups:owned:user', [$page_owner->getDisplayName()]);
}

elgg_push_collection_breadcrumbs('group', 'group');

if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
	elgg_register_title_button('add', 'group', 'group');
}

$content = elgg_list_entities([
	'type' => 'group',
	'owner_guid' => elgg_get_page_owner_guid(),
	'sort_by' => [
		'property' => 'name',
		'direction' => 'ASC',
	],
	'no_results' => elgg_echo('groups:none'),
]);

echo elgg_view_page($title, [
	'content' => $content,
	'filter_id' => 'groups/owner',
	'filter_value' => 'owner',
]);
