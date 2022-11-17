<?php
/**
 * Add bookmark page
 */

$bookmark_guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($bookmark_guid, 'object', 'bookmarks', true);

$bookmark = get_entity($bookmark_guid);

elgg_push_entity_breadcrumbs($bookmark);

echo elgg_view_page(elgg_echo('edit:object:bookmarks'), [
	'filter_id' => 'bookmarks/edit',
	'content' => elgg_view_form('bookmarks/save', ['sticky_enabled' => true], ['entity' => $bookmark]),
]);
