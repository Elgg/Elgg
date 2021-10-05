<?php
/**
 * View a user's read site notifications
 */

$page_owner = elgg_get_page_owner_entity();

$list = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'site_notification',
	'owner_guid' => $page_owner->guid,
	'full_view' => false,
	'metadata_name_value_pairs' => [
		'read' => true,
	],
	'pagination_behaviour' => 'ajax-replace',
]);

if (empty($list)) {
	$content = elgg_view('page/components/no_results', [
		'no_results' => elgg_echo('site_notifications:empty'),
	]);
} else {
	$content = elgg_view_form('site_notifications/process', [], [
		'list' => $list,
		'mark_read' => false,
	]);
}

echo elgg_view_page(elgg_echo('site_notifications'), [
	'content' => $content,
	'sidebar' => false,
	'filter_id' => 'site_notifications',
	'filter_value' => 'read',
]);
