<?php
/**
 * Friends Files
 *
 * @package ElggFile
 */

elgg_push_breadcrumb(elgg_echo('file'), "pg/file/all/");
elgg_push_breadcrumb($owner->name, "pg/file/owner/$owner->username");


$owner = elgg_get_page_owner();

$title = elgg_echo("file:friends",array($owner->name));

elgg_push_context('search');
// offset is grabbed in list_user_friends_objects
$content = list_user_friends_objects($owner->guid, 'file', 10, false);
elgg_pop_context();

$area1 .= get_filetype_cloud($owner->guid, true);

// handle case where friends don't have any files
if (empty($content)) {
	$area2 .= "<p class='margin-top'>".elgg_echo("file:none")."</p>";
} else {
	$area2 .= $content;
}

//get the latest comments on all files
$comments = get_annotations(0, "object", "file", "generic_comment", "", 0, 4, 0, "desc");
$area3 = elgg_view('comments/latest', array('comments' => $comments));

$body = elgg_view_layout('content', array(
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
