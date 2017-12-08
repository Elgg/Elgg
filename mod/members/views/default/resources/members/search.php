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

$results = elgg_search([
	'query' => $query,
	'type' => 'user',
]);

$count = elgg_extract('count', $results);
$users = elgg_extract('entities', $results);

$content = elgg_view_entity_list($users, [
	'count' => $count,
	'full_view' => false,
	'list_type_toggle' => false,
	'pagination' => true,
	'no_results' => elgg_echo('notfound'),
]);

$body = elgg_view_layout('one_sidebar', [
	'title' => $title,
	'content' => $content,
	'sidebar' => elgg_view('members/sidebar'),
	'filter_id' => 'members',
]);

$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
