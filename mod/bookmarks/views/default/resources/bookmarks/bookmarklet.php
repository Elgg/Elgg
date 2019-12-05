<?php
/**
* Elgg bookmarks plugin bookmarklet page
*
* @package Bookmarks
*/

$container_guid = elgg_extract('guid', $vars, elgg_extract('container_guid', $vars));
$container = get_entity($container_guid);
$page_owner = $container;

if ($container instanceof ElggObject) {
	$page_owner = $container->getContainerEntity();
}

elgg_set_page_owner_guid($page_owner->getGUID());

$title = elgg_echo('bookmarks:bookmarklet');

elgg_push_collection_breadcrumbs('object', 'bookmarks', $page_owner);
elgg_push_breadcrumb($title);

echo elgg_view_page($title, [
	'content' => elgg_view('bookmarks/bookmarklet'),
]);
