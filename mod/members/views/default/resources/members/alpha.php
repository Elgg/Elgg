<?php
/**
 * Returns content for the "alphabetical" page
 */

echo elgg_view_page(elgg_echo('members:title:alpha'), [
	'content' => elgg_list_entities([
		'type' => 'user',
		'sort_by' => [
			'property' => 'name',
			'direction' => 'ASC',
		],
	]),
	'sidebar' => elgg_view('members/sidebar'),
	'filter_id' => 'members',
	'filter_value' => 'alpha',
]);
