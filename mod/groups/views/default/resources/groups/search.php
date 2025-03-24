<?php

elgg_push_collection_breadcrumbs('group', 'group');

$tag = (string) get_input('tag');

$content = elgg_list_entities([
	'query' => $tag,
	'type' => 'group',
	'full_view' => false,
	'no_results' => elgg_echo('groups:search:none'),
], 'elgg_search');

$sidebar = elgg_view('groups/sidebar/find');
$sidebar .= elgg_view('groups/sidebar/featured');

echo elgg_view_page(elgg_echo('groups:search:title', [$tag]), [
	'content' => $content,
	'sidebar' => $sidebar,
	'filter_id' => 'groups/search',
	'filter_value' => 'search',
]);
