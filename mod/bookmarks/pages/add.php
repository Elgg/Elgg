<?php

/**
 * Elgg bookmarks plugin add bookmark page
 *
 * @package ElggBookmarks
 */

gatekeeper();

// Get the current page's owner
$page_owner = page_owner_entity();
if ($page_owner === false || is_null($page_owner)) {
	$page_owner = $_SESSION['user'];
	set_page_owner($page_owner->getGUID());
}
if ($page_owner instanceof ElggGroup)
	$container = $page_owner->guid;

$area2 .= elgg_view_title(elgg_echo('bookmarks:this'), false);

// If we've been given a bookmark to edit, grab it
if ($this_guid = get_input('bookmark',0)) {
	$entity = get_entity($this_guid);
	if ($entity->canEdit()) {
		$area2 .= elgg_view('bookmarks/form',array('entity' => $entity, 'container_guid' => $container));
	}
} else {
	$area2 .= elgg_view('bookmarks/form', array('container_guid' => $container));
}

// Format page
$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);

// Draw it
page_draw(elgg_echo('bookmarks:add'),$body);
