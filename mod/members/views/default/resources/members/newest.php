<?php
/**
 * Returns content for the "newest" page
 */

echo elgg_view_page(elgg_echo('members:title:newest'), [
	'content' => elgg_list_entities([
		'type' => 'user',
	]),
	'sidebar' => elgg_view('members/sidebar'),
	'filter_id' => 'members',
	'filter_value' => 'newest',
]);
