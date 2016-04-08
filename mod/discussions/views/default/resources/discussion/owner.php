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

$options = array(
	'type' => 'object',
	'subtype' => 'discussion',
	'limit' => max(20, elgg_get_config('default_limit')),
	'order_by' => 'e.last_action desc',
	'full_view' => false,
	'no_results' => elgg_echo('discussion:none'),
	'preload_owners' => true,
);

if ($target instanceof ElggUser) {
	// Display all discussions started by the user regardless of
	// the entity that is working as a container. See #4878.
	$options['owner_guid'] = $guid;
} else {
	$options['container_guid'] = $guid;
}

$content = elgg_list_entities($options);

$params = array(
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('discussion/sidebar'),
	'filter' => '',
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);