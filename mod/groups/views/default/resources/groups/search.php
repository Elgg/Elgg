<?php

elgg_push_breadcrumb(elgg_echo('groups'), "groups/all");
elgg_push_breadcrumb(elgg_echo('search'));

$tag = get_input('tag');
$display_query = _elgg_get_display_query($tag);
$title = elgg_echo('groups:search:title', [$display_query]);

// groups plugin saves tags as "interests" - see groups_fields_setup() in start.php
$content = elgg_list_entities([
	'query' => $tag,
	'type' => 'group',
	'full_view' => false,
	'no_results' => elgg_echo('groups:search:none'),
], 'elgg_search');

$sidebar = elgg_view('groups/sidebar/find');
$sidebar .= elgg_view('groups/sidebar/featured');

$body = elgg_view_layout('content', [
	'content' => $content,
	'sidebar' => $sidebar,
	'filter' => false,
	'title' => $title,
]);

echo elgg_view_page($title, $body);
