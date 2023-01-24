<?php
/**
 * Returns content for the "popular" page
 */

echo elgg_view_page(elgg_echo('members:title:popular'), [
	'content' => elgg_list_entities_from_relationship_count([
		'type' => 'user',
		'relationship' => 'friend',
		'inverse_relationship' => false,
		'no_results' => elgg_echo('members:list:popular:none'),
	]),
	'sidebar' => elgg_view('members/sidebar'),
	'filter_id' => 'members',
	'filter_value' => 'popular',
	'filter_sorting' => false,
]);
