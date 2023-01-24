<?php
/**
 * List group pages
 */

elgg_group_tool_gatekeeper('pages');

$group = elgg_get_page_owner_entity();

elgg_push_collection_breadcrumbs('object', 'page', $group);

elgg_register_title_button('add', 'object', 'page');

echo elgg_view_page(elgg_echo('collection:object:page'), [
	'content' => elgg_view('pages/listing/group', [
		'entity' => $group,
	]),
	'sidebar' => elgg_view('pages/sidebar', $vars),
	'filter_id' => 'pages/group',
]);
