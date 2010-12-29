<?php
/**
 * Edit a file
 *
 * @package ElggFile
 */

gatekeeper();

$file_guid = (int) get_input('guid');
$file = get_entity($file_guid);
if (!$file) {
	forward();
}

elgg_push_breadcrumb(elgg_echo('file'), "pg/file/all/");
elgg_push_breadcrumb($file->title, $file->getURL());
elgg_push_breadcrumb(elgg_echo('file:edit'));

elgg_set_page_owner_guid($file->getContainerGUID());

if (!$file->canEdit()) {
	forward();
}

$title = elgg_echo('file:edit');
$content = elgg_view_title($title);
$content .= elgg_view("file/upload", array('entity' => $file));

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'header' => '',
));

echo elgg_view_page($title, $body);
