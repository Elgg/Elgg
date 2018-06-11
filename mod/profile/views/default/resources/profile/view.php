<?php

$username = elgg_extract('username', $vars);
if ($username) {
	$user = get_user_by_username($username);
} else {
	$user = elgg_get_logged_in_user_entity();
}

$user_guid = $user ? $user->guid : 0;

elgg_entity_gatekeeper($user_guid, 'user');

elgg_set_page_owner_guid($user_guid);

$content = elgg_view('profile/wrapper', [
	'entity' => $user,
]);

$content .= elgg_view_layout('widgets', [
	'num_columns' => 2,
	'owner_guid' => $user_guid,
]);

$body = elgg_view_layout('default', [
	'content' => $content,
	'title' => $user->getDisplayName(),
	'entity' => $user,
	'sidebar_alt' => elgg_view('profile/owner_block', [
		'entity' => $user,
	]),
	'class' => 'profile',
	'sidebar' => false,
]);

echo elgg_view_page($user->getDisplayName(), $body);
