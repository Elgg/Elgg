<?php

// Get the page's owner
$page_owner = page_owner_entity();
if ($page_owner === false || is_null($page_owner)) {
	$page_owner = get_loggedin_user();
	set_page_owner($page_owner->getGUID());
}

$title = sprintf(elgg_echo("thewire:friends"), $page_owner->name);

$content = elgg_view_title($title);

$content .= list_user_friends_objects($page_owner->getGUID(), 'thewire');

$body = elgg_view_layout("two_column_left_sidebar", '', $content);

page_draw($title, $body);