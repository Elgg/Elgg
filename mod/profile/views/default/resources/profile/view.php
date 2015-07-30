<?php

$username = elgg_extract('username', $vars);
$user = get_user_by_username($username);
elgg_set_page_owner_guid($user->guid);

$content = elgg_view('profile/layout', array('entity' => $user));
$body = elgg_view_layout('one_column', array(
	'content' => $content
));
echo elgg_view_page($user->name, $body);
