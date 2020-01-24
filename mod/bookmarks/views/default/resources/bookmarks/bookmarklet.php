<?php
/**
* Elgg bookmarks plugin bookmarklet page
*/

$container_guid = elgg_extract('guid', $vars, elgg_extract('container_guid', $vars));
$container = get_entity($container_guid);
$page_owner = $container;

if ($container instanceof ElggObject) {
	$page_owner = $container->getContainerEntity();
}

elgg_set_page_owner_guid($page_owner->getGUID());

elgg_push_collection_breadcrumbs('object', 'bookmarks', $page_owner);

echo elgg_view_page(elgg_echo('bookmarks:bookmarklet'), [
	'content' => elgg_view('bookmarks/bookmarklet'),
]);
