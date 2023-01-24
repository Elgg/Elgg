<?php
/**
 * Lists discussions created inside a specific group
 */

elgg_group_tool_gatekeeper('forum');

$group = elgg_get_page_owner_entity();

elgg_push_collection_breadcrumbs('object', 'discussion', $group);

elgg_register_title_button('add', 'object', 'discussion');

echo elgg_view_page(elgg_echo('collection:object:discussion'), [
	'content' => elgg_view('discussion/listing/group', [
		'entity' => $group,
	]),
	'filter_id' => 'discussion/group',
]);
