<?php

elgg_group_tool_gatekeeper('bookmarks');

$group = elgg_get_page_owner_entity();

elgg_register_title_button('add', 'object', 'bookmarks');

elgg_push_collection_breadcrumbs('object', 'bookmarks', $group);

echo elgg_view_page(elgg_echo('collection:object:bookmarks'), [
	'content' => elgg_view('bookmarks/listing/group', [
		'entity' => $group,
	]),
	'filter_id' => 'bookmarks/group',
	'filter_value' => 'all',
]);
