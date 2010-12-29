<?php
/**
 * Elgg file browser
 *
 * @package ElggFile
 */

// access check for closed groups
group_gatekeeper();

$owner = elgg_get_page_owner();

elgg_push_breadcrumb(elgg_echo('file'), "pg/file/all/");
elgg_push_breadcrumb($owner->name);


//set the title
if (elgg_get_page_owner_guid() == get_loggedin_userid()) {
	$title = elgg_echo('file:yours');
	$area1 = elgg_view('page/elements/content_header', array('context' => "mine", 'type' => 'file'));
} else {
	$title = elgg_echo("file:user",array(elgg_get_page_owner()->name));
	$area1 = elgg_view('page/elements/content_header', array('context' => "friends", 'type' => 'file'));
}

// Get objects
elgg_push_context('search');
$offset = (int)get_input('offset', 0);
$content = elgg_list_entities(array('types' => 'object', 'subtypes' => 'file', 'container_guid' => elgg_get_page_owner_guid(), 'limit' => 10, 'offset' => $offset, 'full_view' => FALSE));
elgg_pop_context();

$get_filter = get_filetype_cloud(elgg_get_page_owner_guid());
if ($get_filter) {
	$area1 .= $get_filter;
} else {
	$area2 .= "<p class='margin-top'>".elgg_echo("file:none")."</p>";
}

//get the latest comments on the current users files
$comments = get_annotations(0, "object", "file", "generic_comment", "", 0, 4, 0, "desc",0,0,elgg_get_page_owner_guid());
$area3 = elgg_view('comments/latest', array('comments' => $comments));

$body = elgg_view_layout('content', array(
	'filter_context' => 'mine',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);