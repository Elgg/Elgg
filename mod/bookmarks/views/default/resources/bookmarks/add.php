<?php
/**
 * Add bookmark page
 *
 * @package Bookmarks
 */
elgg_gatekeeper();

$page_owner = elgg_get_page_owner_entity();

$title = elgg_echo('add:object:bookmarks');
elgg_push_breadcrumb(elgg_echo('collection:object:bookmarks'), 'bookmarks/all');
if ($page_owner instanceof ElggGroup) {
	elgg_push_breadcrumb($page_owner->getDisplayName(), "bookmarks/group/{$page_owner->guid}/all");
} else {
	elgg_push_breadcrumb($page_owner->getDisplayName(), "bookmarks/owner/{$page_owner->username}");
}
elgg_push_breadcrumb($title);

$vars = bookmarks_prepare_form_vars();
$content = elgg_view_form('bookmarks/save', [], $vars);

$body = elgg_view_layout('content', [
	'filter' => '',
	'content' => $content,
	'title' => $title,
]);

echo elgg_view_page($title, $body);
