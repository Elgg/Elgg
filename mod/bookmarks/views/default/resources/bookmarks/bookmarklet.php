<?php
/**
* Elgg bookmarks plugin bookmarklet page
*
* @package Bookmarks
*/

elgg_gatekeeper();

$container_guid = elgg_extract('container_guid', $vars);
$container = get_entity($container_guid);
$page_owner = $container;

if ($container instanceof ElggObject) {
	$page_owner = $container->getContainerEntity();
}

elgg_set_page_owner_guid($page_owner->getGUID());

$title = elgg_echo('bookmarks:bookmarklet');

elgg_push_breadcrumb(elgg_echo('collection:object:bookmarks'), 'bookmarks/all');
if ($page_owner instanceof ElggGroup) {
	elgg_push_breadcrumb($page_owner->getDisplayName(), "bookmarks/group/{$page_owner->guid}/all");
} else {
	elgg_push_breadcrumb($page_owner->getDisplayName(), "bookmarks/owner/{$page_owner->username}");
}
elgg_push_breadcrumb($title);

$content = elgg_view('bookmarks/bookmarklet');

$body = elgg_view_layout('content', [
	'content' => $content,
	'title' => $title,
	'filter' => false
]);

echo elgg_view_page($title, $body);
