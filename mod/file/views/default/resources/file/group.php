<?php
/**
 * Individual's or group's files
 */

elgg_group_tool_gatekeeper('file');

$container = elgg_get_page_owner_entity();

elgg_push_collection_breadcrumbs('object', 'file', $container);

elgg_register_title_button('add', 'object', 'file');

$title = elgg_echo('collection:object:file:owner', [$container->getDisplayName()]);

$listing_params = $vars;
$listing_params['entity'] = $container;

echo elgg_view_page($title, [
	'content' => elgg_view('file/listing/group', $listing_params),
	'sidebar' => elgg_view('file/sidebar'),
	'filter_id' => 'file/group',
]);
