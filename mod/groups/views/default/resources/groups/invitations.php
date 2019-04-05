<?php

$username = elgg_extract('username', $vars);
if ($username) {
	$user = get_user_by_username($username);
} else {
	$user = elgg_get_logged_in_user_entity();
}

if (!$user || !$user->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

elgg_push_breadcrumb(elgg_echo('groups'), "groups/all");

elgg_set_page_owner_guid($user->guid);

$title = elgg_echo('groups:invitations');

$content = elgg_view('groups/invitationrequests');

$params = [
	'content' => $content,
	'title' => $title,
	'filter' => '',
];
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
