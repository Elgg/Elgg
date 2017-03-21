<?php

$username = elgg_extract('username', $vars);
$user = get_user_by_username($username);
elgg_set_page_owner_guid($user->guid);

$content = elgg_view('profile/wrapper', [
	'entity' => $user,
]);
$content .= elgg_view_layout('widgets', [
	'num_columns' => 2,
	'owner_guid' => $user->guid,
]);

$body = elgg_view_layout('one_column', [
	'content' => $content,
	'title' => $user->getDisplayName(),
]);
echo elgg_view_page($user->name, $body);
