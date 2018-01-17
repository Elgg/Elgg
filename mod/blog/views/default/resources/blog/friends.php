<?php

$page_type = 'friends';
$username = elgg_extract('username', $vars);

$user = get_user_by_username($username);
if (!$user) {
	throw new \Elgg\EntityNotFoundException();
}

$params = [
	'filter_context' => 'friends',
	'title' => elgg_echo('collection:object:blog:friends'),
];

elgg_push_collection_breadcrumbs('object', 'blog', $user, true);

elgg_register_title_button('blog', 'add', 'object', 'blog');

$options = [
	'type' => 'object',
	'subtype' => 'blog',
	'full_view' => false,
	'relationship' => 'friend',
	'relationship_guid' => $user->getGUID(),
	'relationship_join_on' => 'owner_guid',
	'no_results' => elgg_echo('blog:none'),
	'preload_owners' => true,
	'preload_containers' => true,
];

$params['content'] = elgg_list_entities($options);

$sidebar = elgg_extract('sidebar', $params, '');
$sidebar .= elgg_view('blog/sidebar', ['page' => $page_type]);
$params['sidebar'] = $sidebar;

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
