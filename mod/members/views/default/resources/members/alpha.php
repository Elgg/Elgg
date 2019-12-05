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

echo elgg_view_page(elgg_echo('members:title:alpha'), [
	'content' => $content,
	'sidebar' => elgg_view('members/sidebar'),
	'filter_id' => 'members',
	'filter_value' => 'alpha',
]);
