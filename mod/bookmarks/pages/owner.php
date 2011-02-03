<?php
/**
 * Elgg bookmarks plugin everyone page
 *
 * @package ElggBookmarks
 */

elgg_push_context('bookmarks');
elgg_push_breadcrumb(elgg_echo('bookmarks'), 'pg/bookmarks/all');

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner) {
	$page_owner = get_loggedin_userid();
	elgg_set_page_owner_guid($page_owner);
}

elgg_push_breadcrumb($page_owner->name, "pg/bookmarks/owner/$page_owner->username");

$offset = (int)get_input('offset', 0);
$content .= elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'bookmarks',
	'owner_guid' => $page_owner->guid,
	'limit' => 10,
	'offset' => $offset,
	'full_view' => false,
	'view_toggle_type' => false
));

$title = elgg_echo('bookmarks:owner', array($page_owner->name));

$body = elgg_view_layout('content', array(
	'filter_context' => 'mine',
	'content' => $content,
	'title' => $title
));

echo elgg_view_page($title, $body);
elgg_pop_context();