<?php
/**
 * List a user's or group's pages
 */

$owner = elgg_get_page_owner_entity();
if (!$owner) {
	throw new \Elgg\EntityNotFoundException();
}

// access check for closed groups
elgg_group_gatekeeper();

$title = elgg_echo('collection:object:page:owner', [$owner->getDisplayName()]);

elgg_push_breadcrumb(elgg_echo('pages'), 'pages/all');
elgg_push_breadcrumb($owner->getDisplayName());

elgg_register_title_button('pages', 'add', 'object', 'page');

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'page',
	'metadata_name_value_pairs' => [
		'parent_guid' => 0,
	],
	'container_guid' => $owner->guid,
	'full_view' => false,
	'no_results' => elgg_echo('pages:none'),
	'preload_owners' => true,
]);

$filter_context = '';
if (elgg_get_page_owner_guid() == elgg_get_logged_in_user_guid()) {
	$filter_context = 'mine';
}

$sidebar = elgg_view('pages/sidebar/navigation');
$sidebar .= elgg_view('pages/sidebar');

$params = [
	'filter_context' => $filter_context,
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
];

if ($owner instanceof ElggGroup) {
	$params['filter'] = '';
}

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
