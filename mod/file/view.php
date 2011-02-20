<?php
/**
 * View a file
 *
 * @package ElggFile
 */

$file = get_entity(get_input('guid'));

$owner = elgg_get_page_owner_entity();

elgg_push_breadcrumb(elgg_echo('file'), 'pg/file/all');

$crumbs_title = $owner->name;
if (elgg_instanceof($owner, 'group')) {
	elgg_push_breadcrumb($crumbs_title, "pg/file/group/$owner->guid/owner");
} else {
	elgg_push_breadcrumb($crumbs_title, "pg/file/owner/$owner->username");
}

$title = $file->title;

elgg_push_breadcrumb($title);

$content = elgg_view_entity($file, true);
$content .= elgg_view_comments($file);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'header' => '',
));

echo elgg_view_page($title, $body);
