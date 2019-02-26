<?php

if (!elgg_get_config('allow_registration')) {
	forward();
}

elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

$title = elgg_echo('friends:invite');

$body = elgg_view_layout('default', [
	'content' => elgg_view_form('friends/invite'),
	'title' => $title,
	'show_owner_block_menu' => false,
]);

echo elgg_view_page($title, $body);
