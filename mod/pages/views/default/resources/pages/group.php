<?php
/**
 * List group pages
 */

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'group');

elgg_group_tool_gatekeeper('pages', $guid);

$group = get_entity($guid);

elgg_push_collection_breadcrumbs('object', 'page', $group);

elgg_register_title_button('pages', 'add', 'object', 'page');


echo elgg_view_page(elgg_echo('collection:object:page'), [
	'content' => elgg_view('pages/listing/group', [
		'entity' => $group,
	]),
	'sidebar' => elgg_view('pages/sidebar', $vars),
	'filter_id' => 'pages/group',
]);
