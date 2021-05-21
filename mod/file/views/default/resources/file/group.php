<?php
/**
 * Individual's or group's files
 */

$group_guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($group_guid, 'group');

elgg_group_tool_gatekeeper('file', $group_guid);

$container = get_entity($group_guid);

elgg_push_collection_breadcrumbs('object', 'file', $container);

elgg_register_title_button('file', 'add', 'object', 'file');

$title = elgg_echo('collection:object:file:owner', [$container->getDisplayName()]);

$listing_params = $vars;
$listing_params['entity'] = $container;

echo elgg_view_page($title, [
	'content' => elgg_view('file/listing/group', $listing_params),
	'sidebar' => elgg_view('file/sidebar'),
	'filter_id' => 'file/group',
]);
