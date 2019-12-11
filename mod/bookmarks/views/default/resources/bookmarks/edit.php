<?php
/**
 * Add bookmark page
 *
 * @package ElggBookmarks
 */

$bookmark_guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($bookmark_guid, 'object', 'bookmarks', true);

$bookmark = get_entity($bookmark_guid);

$title = elgg_echo('edit:object:bookmarks');

elgg_push_entity_breadcrumbs($bookmark);

$vars = bookmarks_prepare_form_vars($bookmark);
$content = elgg_view_form('bookmarks/save', [], $vars);

$body = elgg_view_layout('default', [
	'filter_id' => 'bookmarks/edit',
	'content' => $content,
	'title' => $title,
]);

echo elgg_view_page($title, $body);
