<?php

$user_guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($user_guid, 'user');
$user = get_user($user_guid);

$title = $user->getDisplayName();

$params = [
	'content' => elgg_view_entity($user),
	'title' => $title,
	'sidebar' => false,
];
$body = elgg_view_layout('default', $params);

echo elgg_view_page($title, $body);
