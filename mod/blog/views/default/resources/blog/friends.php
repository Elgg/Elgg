<?php

elgg_load_library('elgg:blog');

// push all blogs breadcrumb
elgg_push_breadcrumb(elgg_echo('blog:blogs'), elgg_generate_url('collection:object:blog:all'));

$page_type = 'friends';
$username = elgg_extract('username', $vars);

$user = get_user_by_username($username);
if (!$user) {
	throw new \Elgg\EntityNotFoundException();
}

$params = [
	'filter_context' => 'friends',
	'title' => elgg_echo('blog:title:friends'),
];

$crumbs_title = $user->name;
elgg_push_breadcrumb($crumbs_title, elgg_generate_url('collection:object:blog:owner', ['username' => $user->username]));
elgg_push_breadcrumb(elgg_echo('friends'));

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

$params['sidebar'] = elgg_view('blog/sidebar', ['page' => $page_type]);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
