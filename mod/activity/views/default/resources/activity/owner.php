<?php
/**
 * Show river activity from user
 */

$page_owner = elgg_get_page_owner_entity();

if ($page_owner->guid === elgg_get_logged_in_user_guid()) {
	$title = elgg_echo('river:mine');
	$page_filter = 'mine';
} else {
	$title = elgg_echo('river:owner', [$page_owner->getDisplayName()]);
	$page_filter = 'subject';
}

elgg_push_breadcrumb($page_owner->getDisplayName(), $page_owner->getURL());

$content = elgg_view('river/listing/owner', [
	'entity' => $page_owner,
	'entity_type' => preg_replace('[\W]', '', get_input('type', 'all')),
	'entity_subtype' => preg_replace('[\W]', '', get_input('subtype', '')),
	'show_filter' => true,
]);

echo elgg_view_page($title, [
	'content' => $content,
	'sidebar' => elgg_view('river/sidebar'),
	'filter_value' => $page_filter,
	'class' => 'elgg-river-layout',
]);
