<?php
/**
 * Returns content for the "online" page
 */

$content = get_online_users();

$title = elgg_echo('members:title:online');

$body = elgg_view_layout('default', [
	'content' => $content,
	'sidebar' => elgg_view('members/sidebar'),
	'title' => $title,
	'filter_id' => 'members',
	'filter_value' => 'online',
]);

echo elgg_view_page($title, $body);
