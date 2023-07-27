<?php
/**
 * Show river activity from friends
 */

$page_owner = elgg_get_page_owner_entity();

elgg_push_breadcrumb($page_owner->getDisplayName(), $page_owner->getURL());

$content = elgg_view('river/listing/friends', [
	'entity' => $page_owner,
	'entity_type' => preg_replace('[\W]', '', get_input('type', 'all')),
	'entity_subtype' => preg_replace('[\W]', '', get_input('subtype', '')),
	'show_filter' => true,
]);

echo elgg_view_page(elgg_echo('river:friends'), [
	'content' => $content,
	'sidebar' => elgg_view('river/sidebar'),
	'filter_value' => $page_owner->guid === elgg_get_logged_in_user_guid() ? 'friends' : 'none',
	'class' => 'elgg-river-layout',
]);
