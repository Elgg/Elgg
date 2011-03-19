<?php
/**
 * Friends Files
 *
 * @package ElggFile
 */

$owner = elgg_get_page_owner_entity();

elgg_push_breadcrumb(elgg_echo('file'), "file/all");
elgg_push_breadcrumb($owner->name, "file/owner/$owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));


$title = elgg_echo("file:friends", array($owner->name));

// offset is grabbed in list_user_friends_objects
$content = list_user_friends_objects($owner->guid, 'file', 10, false);
if (!$content) {
	$content = elgg_echo("file:none");
}

$sidebar = file_get_type_cloud($owner->guid, true);

$body = elgg_view_layout('content', array(
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
));

echo elgg_view_page($title, $body);
