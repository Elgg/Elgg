<?php

$username = elgg_extract('username', $vars);
if ($username) {
	$user = get_user_by_username($username);
	$guid = $user->guid;
} else {
	// Backward compatibility
	$guid = elgg_extract('guid', $vars);
}

elgg_entity_gatekeeper($guid);

$target = get_entity($guid);

if ($target instanceof ElggGroup) {
	// Before Elgg 2.0 only groups could work as containers for discussions.
	// Back then the URL that listed all discussions within a group was
	// "discussion/owner/<guid>". Now that any entity can be used as a
	// container, we use the standard "<content type>/group/<guid>" URL
	// also with discussions.
	forward(elgg_generate_url('collection:object:discussion:group', [
		'guid' => $guid,
	]), '301');
}

elgg_push_collection_breadcrumbs('object', 'discussion', $target);

elgg_register_title_button('discussion', 'add', 'object', 'discussion');

$title = elgg_echo('collection:object:discussion');

$content = elgg_view('discussion/listing/owner', [
	'entity' => $target,
]);

$params = [
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('discussion/sidebar'),
	'filter' => '',
];

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
