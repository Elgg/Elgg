<?php
/**
 * Returns content for the "newest" page
 */

$content = elgg_list_entities([
	'type' => 'user',
]);

$title = elgg_echo('members:title:newest');

$body = elgg_view_layout('default', [
	'content' => $content,
	'sidebar' => elgg_view('members/sidebar'),
	'title' => $title,
	'filter_id' => 'members',
	'filter_value' => 'newest',
]);

echo elgg_view_page($title, $body);
