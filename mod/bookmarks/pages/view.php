<?php
/**
 * View a bookmark
 *
 * @package ElggBookmarks
 */

$bookmark = get_entity(get_input('guid'));

elgg_set_page_owner_guid($bookmark->getContainerGUID());
$owner = elgg_get_page_owner_entity();

elgg_push_breadcrumb(elgg_echo('bookmarks'), 'pg/bookmarks/all');

$crumbs_title = elgg_echo('blog:owned_blogs', array($owner->name));
if (elgg_instanceof($owner, 'group')) {
	elgg_push_breadcrumb($crumbs_title, "pg/bookmarks/group/$owner->guid/owner");
} else {
	elgg_push_breadcrumb($crumbs_title, "pg/bookmarks/owner/$owner->username");
}

$title = $bookmark->title;

elgg_push_breadcrumb($title);

$content = elgg_view_entity($bookmark, true);
$content .= elgg_view_comments($bookmark);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'header' => '',
));

echo elgg_view_page($title, $body);
