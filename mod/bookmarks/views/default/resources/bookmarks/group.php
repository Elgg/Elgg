<?php

$group_guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($group_guid, 'group');

$group = get_entity($group_guid);

elgg_register_title_button('bookmarks', 'add', 'object', 'bookmarks');

elgg_push_collection_breadcrumbs('object', 'bookmarks', $group);

$content = elgg_view('bookmarks/listing/group', [
	'entity' => $group,
]);

echo elgg_view_page(elgg_echo('collection:object:bookmarks'), [
	'content' => $content,
	'filter_id' => 'bookmarks/group',
	'filter_value' => 'all',
]);
