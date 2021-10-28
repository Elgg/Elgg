<?php
/**
 * View a user's read site notifications
 */

$page_owner = elgg_get_page_owner_entity();

$options = [
	'type' => 'object',
	'subtype' => 'site_notification',
	'owner_guid' => $page_owner->guid,
	'full_view' => false,
	'metadata_name_value_pairs' => [
		'read' => true,
	],
	'pagination' => true,
	'pagination_behaviour' => 'ajax-replace',
];

$list = elgg_list_entities($options);
if (empty($list)) {
	$options['no_results'] = elgg_echo('site_notifications:empty');
	$options['count'] = elgg_count_entities($options);
	
	$content = elgg_view('page/components/no_results', $options);
	$content .= elgg_view('page/components/list/out_of_bounds', $options);
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
