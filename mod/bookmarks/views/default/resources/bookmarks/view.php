<?php
/**
 * View a bookmark
 *
 * @package ElggBookmarks
 */

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'bookmarks');

$bookmark = get_entity($guid);

elgg_push_entity_breadcrumbs($bookmark, flase);

$title = $bookmark->getDisplayName();

$content = elgg_view_entity($bookmark, [
	'full_view' => true,
	'show_responses' => true,
]);

$body = elgg_view_layout('default', [
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'entity' => $entity,
	'sidebar' => elgg_view('object/bookmarks/elements/sidebar', [
		'entity' => $entity,
	]),
]);

echo elgg_view_page($title, $body, 'default', [
	'entity' => $entity,
]);
