<?php
/**
 * List group pages
 */

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'group');

$group = get_entity($guid);

$title = elgg_echo('collection:object:page');

elgg_push_collection_breadcrumbs('object', 'page', $group);

elgg_register_title_button('pages', 'add', 'object', 'page');

$content = elgg_view('pages/listing/group', [
	'entity' => $group,
]);

$sidebar = elgg_view('pages/sidebar/navigation', $vars);
$sidebar .= elgg_view('pages/sidebar');

$body = elgg_view_layout('default', [
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
]);

echo elgg_view_page($title, $body);
