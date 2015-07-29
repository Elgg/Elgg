<?php

elgg_gatekeeper();

$username = elgg_extract('username', $vars);
if ($username) {
	$user = get_user_by_username($username);
	elgg_set_page_owner_guid($user->guid);
} else {
	$user = elgg_get_logged_in_user_entity();
	elgg_set_page_owner_guid($user->guid);
}

if (!$user || !$user->canEdit()) {
	register_error(elgg_echo('noaccess'));
	forward('');
}

$title = elgg_echo('groups:invitations');
elgg_push_breadcrumb($title);

$content = elgg_view('groups/invitationrequests');

$params = array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);