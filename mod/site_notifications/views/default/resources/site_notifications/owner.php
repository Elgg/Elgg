<?php
/**
 * View a user's site notifications
 */

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner instanceof ElggUser || !$page_owner->canEdit()) {
	// must have access to view
	throw new \Elgg\EntityPermissionsException(elgg_echo('site_notifications:no_access'));
}

elgg_load_js('elgg.site_notifications');

$title = elgg_echo('site_notifications');

$list = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'site_notification',
	'owner_guid' => $page_owner->guid,
	'full_view' => false,
	'metadata_name_value_pairs' => [
		'read' => false,
	],
]);

if (empty($list)) {
	$content = elgg_view('page/components/no_results', [
		'no_results' => elgg_echo('site_notifications:empty'),
	]);
} else {
	$body_vars = [
		'list' => $list
	];
	
	$content = elgg_view_form("site_notifications/process", [], $body_vars);
}

$body = elgg_view_layout('content', [
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'show_owner_block_menu' => false,
]);

echo elgg_view_page($title, $body);
