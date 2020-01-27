<?php
/**
 * Returns content for the "newest" page
 */

$content = elgg_list_entities([
	'type' => 'user',
]);

echo elgg_view_page(elgg_echo('members:title:newest'), [
	'content' => $content,
	'sidebar' => elgg_view('members/sidebar'),
	'filter_id' => 'members',
	'filter_value' => 'newest',
]);
