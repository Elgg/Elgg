<?php

$username = elgg_extract('username', $vars);
$user = get_user_by_username($username);

if (!$user) {
	forward('', '404');
}

echo elgg_view_profile_page($user);