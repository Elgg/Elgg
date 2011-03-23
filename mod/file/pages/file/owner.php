<?php
/**
 * Individual's or group's files
 *
 * @package ElggFile
 */

// access check for closed groups
group_gatekeeper();

$owner = elgg_get_page_owner_entity();

elgg_push_breadcrumb(elgg_echo('file'), "file/all");
elgg_push_breadcrumb($owner->name);

$params = array();

if ($owner->guid == elgg_get_logged_in_user_guid()) {
	// user looking at own files
	$title = elgg_echo('file:yours');
	$params['filter_context'] = 'mine';
} else if (elgg_instanceof($owner, 'user')) {
	// someone else's files
	$title = elgg_echo("file:user", array($owner->name));
	// do not show button or select a tab when viewing someone else's posts
	$params['filter_context'] = 'none';
	$params['buttons'] = '';
} else {
	// group files
	$title = elgg_echo("file:user", array($owner->name));
	$params['filter'] = '';
}

// List files
$content = elgg_list_entities(array(
	'types' => 'object',
	'subtypes' => 'file',
	'container_guid' => $owner->guid,
	'limit' => 10,
	'full_view' => FALSE,
));
if (!$content) {
	$content = elgg_echo("file:none");
}

$sidebar = file_get_type_cloud(elgg_get_page_owner_guid());
$sidebar = elgg_view('file/sidebar');

$params['content'] = $content;
$params['title'] = $title;
$params['sidebar'] = $sidebar;

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
