<?php

elgg_push_breadcrumb(elgg_echo('groups'), elgg_generate_url('collection:group:group:all'));

$tag = get_input('tag');
$display_query = _elgg_get_display_query($tag);
$title = elgg_echo('groups:search:title', [$display_query]);

$content = elgg_list_entities([
	'query' => $tag,
	'type' => 'group',
	'full_view' => false,
	'no_results' => elgg_echo('groups:search:none'),
], 'elgg_search');

$sidebar = elgg_view('groups/sidebar/find');
$sidebar .= elgg_view('groups/sidebar/featured');

echo elgg_view_page($title, [
	'content' => $content,
	'sidebar' => $sidebar,
	'filter_id' => 'groups/search',
	'filter_value' => 'search',
]);
