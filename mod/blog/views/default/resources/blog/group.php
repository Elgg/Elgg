<?php

$lower = elgg_extract('lower', $vars);
$upper = elgg_extract('upper', $vars);

elgg_group_tool_gatekeeper('blog');

$group = elgg_get_page_owner_entity();

elgg_register_title_button('add', 'object', 'blog');

elgg_push_collection_breadcrumbs('object', 'blog', $group);

$title = elgg_echo('collection:object:blog:group');
if ($lower) {
	$title .= ': ' . elgg_echo('date:month:' . date('m', $lower), [date('Y', $lower)]);
}

$content = elgg_view('blog/listing/group', [
	'entity' => $group,
	'created_after' => $lower,
	'created_before' => $upper,
]);

echo elgg_view_page($title, [
	'content' => $content,
	'sidebar' => elgg_view('blog/sidebar', [
		'page' => 'group',
		'entity' => $group,
	]),
	'filter_id' => 'blog/group',
	'filter_value' => 'all',
]);
