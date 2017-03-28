<?php

$page_type = elgg_extract('page_type', $vars);
$username = elgg_extract('username', $vars);

$user = get_user_by_username($username);
if (!$user) {
	forward('', '404');
}

$listing = [
	'identifier' => 'blog',
	'type' => 'owner',
	'target' => $user,
	'entity_type' => 'object',
	'entity_subtype' => 'blog',
];

echo elgg_view_listing_page($listing, [
	'no_results' => elgg_echo('blog:none'),
], [
	'sidebar' => elgg_view('blog/sidebar', [
		'page' => $page_type,
	]),
]);
