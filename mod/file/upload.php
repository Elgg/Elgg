<?php
/**
 * Upload a new file
 *
 * @package ElggFile
 */

elgg_set_page_owner_guid(get_input('guid'));
$owner = elgg_get_page_owner();

gatekeeper();
group_gatekeeper();

elgg_push_breadcrumb(elgg_echo('file'), "pg/file/all/");
if (elgg_instanceof($owner, 'user')) {
	elgg_push_breadcrumb($owner->name, "pg/file/owner/$owner->username");
} else {
	elgg_push_breadcrumb($owner->name, "pg/file/group/$owner->guid/owner");
}
elgg_push_breadcrumb(elgg_echo('file:new'));

$container_guid = elgg_get_page_owner_guid();

$title = elgg_echo('file:upload');

$content = elgg_view_title($title);

$content .= elgg_view("file/upload", array('container_guid' => $container_guid));
$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'header' => '',
));

echo elgg_view_page($title, $body);
