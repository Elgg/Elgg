<?php
/**
 * Lists discussions created inside a specific group
 */

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'group');

$group = get_entity($guid);

elgg_push_collection_breadcrumbs('object', 'discussion', $group);

elgg_register_title_button('discussion', 'add', 'object', 'discussion');

$title = elgg_echo('collection:object:discussion');

$content = elgg_view('discussion/listing/group', [
	'entity' => $group,
]);

$params = [
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('discussion/sidebar'),
	'filter' => '',
];

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
