<?php
/**
 * Returns content for the "alphabetical" page
 */

$content = elgg_list_entities([
	'type' => 'user',
	'order_by_metadata' => [
		'name' => 'name',
		'direction' => 'ASC',
	],
]);

$title = elgg_echo('members:title:alpha');

$body = elgg_view_layout('default', [
	'content' => $content,
	'sidebar' => elgg_view('members/sidebar'),
	'title' => $title,
	'filter_id' => 'members',
	'filter_value' => 'alpha',
]);

echo elgg_view_page($title, $body);
