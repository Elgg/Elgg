<?php

$guid = elgg_extract('guid', $vars);

$target = get_entity($guid);

if ($target instanceof ElggGroup) {
	// Before Elgg 2.0 only groups could work as containers for discussions.
	// Back then the URL that listed all discussions within a group was
	// "discussion/owner/<guid>". Now that any entity can be used as a
	// container, we use the standard "<content type>/group/<guid>" URL
	// also with discussions.
	forward("discussion/group/$guid", '301');
}

elgg_set_page_owner_guid($guid);

elgg_push_breadcrumb(elgg_echo('item:object:discussion'));

elgg_register_title_button('discussion', 'add', 'object', 'discussion');

$title = elgg_echo('item:object:discussion');

$content = elgg_view('discussion/listing/owner', [
	'entity' => $target,
]);

$params = array(
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('discussion/sidebar'),
	'filter' => '',
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);