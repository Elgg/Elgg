<?php
/**
 * Elgg bookmarks plugin everyone page
 *
 * @package Bookmarks
 */
elgg_group_gatekeeper();

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner) {
	forward('', '404');
}

elgg_push_breadcrumb($page_owner->name);

elgg_register_title_button();

$options = [
	'type' => 'object',
	'subtype' => 'bookmarks',
	'full_view' => false,
	'view_toggle_type' => false,
	'no_results' => elgg_echo('bookmarks:none'),
	'preload_owners' => true,
	'distinct' => false,
];

if ($page_owner instanceof ElggGroup) {
	$options['container_guid'] = $page_owner->guid;
} else {
	$options['owner_guid'] = $page_owner->guid;
}

$content .= elgg_list_entities($options);

$title = elgg_echo('bookmarks:owner', array($page_owner->name));

$filter_context = '';
if ($page_owner->getGUID() == elgg_get_logged_in_user_guid()) {
	$filter_context = 'mine';
}

$vars = array(
	'filter_context' => $filter_context,
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('bookmarks/sidebar'),
);

// don't show filter if out of filter context
if ($page_owner instanceof ElggGroup) {
	$vars['filter'] = false;
}

$body = elgg_view_layout('content', $vars);

echo elgg_view_page($title, $body);