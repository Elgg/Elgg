<?php
/**
 * Members search page
 */

$query = get_input('member_query');

if (empty($query)) {
	forward('members');
}

$display_query = _elgg_get_display_query($query);
$title = elgg_echo('members:title:search', [$display_query]);

$content = elgg_list_entities([
	'query' => $query,
	'type' => 'user',
	'list_type_toggle' => false,
	'no_results' => true,
], 'elgg_search');

$body = elgg_view_layout('one_sidebar', [
	'title' => $title,
	'content' => $content,
	'sidebar' => elgg_view('members/sidebar'),
	'filter_id' => 'members',
	'filter_value' => 'search',
]);

echo elgg_view_page($title, $body);
