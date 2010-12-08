<?php

/**
 * Elgg bookmarks plugin bookmarklet page
 * 
 * @package ElggBookmarks
 */

// Start engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

gatekeeper();
		
// Get the current page's owner
$page_owner = elgg_get_page_owner();
if ($page_owner === false || is_null($page_owner) && (get_loggedin_user())) {
	$page_owner = get_loggedin_user();
	set_page_owner($page_owner->getGUID());
}
		
// get the content area header
$area1 = elgg_view('page/elements/content_header', array('context' => "mine", 'type' => 'bookmarks'));
		
// List bookmarks
$area2 = elgg_view_title(elgg_echo('bookmarks:bookmarklet'));
$area2 .= elgg_view('bookmarks/bookmarklet', array('pg_owner' => $page_owner));
		
// if logged in, get the bookmarklet
$area3 = elgg_view("bookmarks/bookmarklet");
		
// Format
$content = $area1 . $area2 . $area3;
$body = elgg_view_layout('one_column_with_sidebar', array('content' => $content));
		
// Draw it
echo elgg_view_page(elgg_echo('bookmarks:bookmarklet'),$body);