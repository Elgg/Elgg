<?php
/**
 * Show a list of all site members
 */

echo elgg_view_page(elgg_echo('members:title:all'), [
	'content' => elgg_list_entities([
		'type' => 'user',
	]),
	'sidebar' => elgg_view('members/sidebar'),
	'filter_id' => 'members',
	'filter_value' => 'all',
]);
