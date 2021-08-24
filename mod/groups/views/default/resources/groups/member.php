<?php

$username = elgg_extract('username', $vars);
if ($username) {
	$user = get_user_by_username($username);
	elgg_set_page_owner_guid($user->guid);
} else {
	$user = elgg_get_logged_in_user_entity();
	elgg_set_page_owner_guid($user->guid);
}

$page_owner = elgg_get_page_owner_entity();

if ($page_owner->guid == elgg_get_logged_in_user_guid()) {
	$title = elgg_echo('groups:yours');
} else {
	$title = elgg_echo('groups:user', [$page_owner->getDisplayName()]);
}

elgg_push_breadcrumb(elgg_echo('groups'), elgg_generate_url('collection:group:group:all'));

if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
	elgg_register_title_button('groups', 'add', 'group', 'group');
}

$content = elgg_list_entities([
	'type' => 'group',
	'relationship' => 'member',
	'relationship_guid' => elgg_get_page_owner_guid(),
	'inverse_relationship' => false,
	'full_view' => false,
	'order_by_metadata' => [
		'name' => 'name',
		'direction' => 'ASC',
	],
	'no_results' => elgg_echo('groups:none'),
]);

echo elgg_view_page($title, [
	'content' => $content,
	'filter_id' => 'groups/member',
	'filter_value' => 'member',
]);
