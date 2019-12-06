<?php
/**
 * Lists discussions created inside a specific group
 */

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'group');

elgg_group_tool_gatekeeper('forum', $guid);

$group = get_entity($guid);

elgg_push_collection_breadcrumbs('object', 'discussion', $group);

elgg_register_title_button('discussion', 'add', 'object', 'discussion');

$title = elgg_echo('collection:object:discussion');

$content = elgg_view('discussion/listing/group', [
	'entity' => $group,
]);

$body = elgg_view_layout('default', [
	'title' => $title,
	'content' => $content,
]);

echo elgg_view_page($title, $body);
