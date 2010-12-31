<?php
/**
 * Individual's or group's files
 *
 * @package ElggFile
 */

elgg_set_page_owner_guid(get_input('guid'));

// access check for closed groups
group_gatekeeper();

$owner = elgg_get_page_owner();

elgg_push_breadcrumb(elgg_echo('file'), "pg/file/all/");
elgg_push_breadcrumb($owner->name);

$params = array();

if ($owner->guid == get_loggedin_userid()) {
	// user looking at own files
	$title = elgg_echo('file:yours');
	$params['filter_context'] = 'mine';
} else if (elgg_instanceof($owner, 'user')) {
	// someone else's files
	$title = elgg_echo("file:user", array($owner()->name));
	// do not show button or select a tab when viewing someone else's posts
	$params['filter_context'] = 'none';
	$params['buttons'] = '';
} else {
	// group files
	$title = elgg_echo("file:user", array($owner->name));
	$params['filter'] = '';
	if ($owner->isMember(get_loggedin_user())) {
		$url = "pg/file/new/$owner->guid";
		$vars = array(
			'href' => $url,
			'text' => elgg_echo("file:new"),
			'class' => 'elgg-action-button',
		);
		$button = elgg_view('output/url', $vars);
		$params['buttons'] = $button;
	} else {
		$params['buttons'] = '';
	}
}

// Get objects
$content = elgg_list_entities(array(
	'types' => 'object',
	'subtypes' => 'file',
	'container_guid' => $owner->guid,
	'limit' => 10,
	'full_view' => FALSE,
));

$get_filter = get_filetype_cloud(elgg_get_page_owner_guid());
if ($get_filter) {
	$area1 .= $get_filter;
} else {
	$area2 .= "<p class='margin-top'>".elgg_echo("file:none")."</p>";
}

//get the latest comments on the current users files
$comments = get_annotations(0, "object", "file", "generic_comment", "", 0, 4, 0, "desc",0,0,elgg_get_page_owner_guid());
$area3 = elgg_view('comments/latest', array('comments' => $comments));

$params['content'] = $content;
$params['title'] = $title;

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
