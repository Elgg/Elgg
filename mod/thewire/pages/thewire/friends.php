<?php
/**
 * Wire posts of your friends
 */

$owner = elgg_get_page_owner_entity();

$title = elgg_echo('thewire:friends');

elgg_push_breadcrumb(elgg_echo('thewire'), "thewire/all");
elgg_push_breadcrumb($owner->name, "thewire/owner/$owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

$content = list_user_friends_objects($owner->guid, 'thewire', 15, false);

$body = elgg_view_layout('content', array(
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
	'buttons' => false,
));

echo elgg_view_page($title, $body);
