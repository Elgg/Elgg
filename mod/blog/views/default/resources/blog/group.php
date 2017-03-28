<?php

$subpage = elgg_extract('subpage', $vars);
$page_type = elgg_extract('page_type', $vars);
$group_guid = elgg_extract('group_guid', $vars);
$lower = elgg_extract('lower', $vars);
$upper = elgg_extract('upper', $vars);

$group = get_entity($group_guid);

if (!elgg_instanceof($group, 'group')) {
	forward('', '404');
}

$sidebar = elgg_view('blog/sidebar', ['page' => $page_type]);

if (!isset($subpage) || $subpage == 'all') {
	$listing = [
		'identifier' => 'blog',
		'type' => 'group',
		'target' => $group,
		'entity_type' => 'object',
		'entity_subtype' => 'blog',
	];

	echo elgg_view_listing_page($listing, [
		'no_results' => elgg_echo('blog:none'),
			], [
		'sidebar' => elgg_view('blog/sidebar', [
			'page' => $page_type,
		]),
	]);
} else {

	$listing = [
		'identifier' => 'blog',
		'type' => 'archive',
		'target' => $group,
		'entity_type' => 'object',
		'entity_subtype' => 'blog',
	];

	echo elgg_view_listing_page($listing, [
		'no_results' => elgg_echo('blog:none'),
		'lower' => $lower,
		'upper' => $upper,
			], [
		'title' => elgg_echo('date:month:' . date('m', $lower), [date('Y', $lower)]),
		'sidebar' => elgg_view('blog/sidebar', [
			'page' => $page_type,
		]),
	]);
}
