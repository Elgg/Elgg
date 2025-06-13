<?php
/**
 * Elgg sent messages page
 */

/* @var $page_owner \ElggUser */
$page_owner = elgg_get_page_owner_entity();

elgg_push_collection_breadcrumbs('object', 'messages', $page_owner);

elgg_register_title_button('add', 'object', 'messages');

$title = elgg_echo('messages:sentmessages', [$page_owner->getDisplayName()]);

$options = [
	'type' => 'object',
	'subtype' => 'messages',
	'metadata_name_value_pairs' => [
		'name' => 'fromId',
		'value' => $page_owner->guid,
	],
	'owner_guid' => $page_owner->guid,
	'bulk_actions' => true,
	'pagination' => true,
	'pagination_behaviour' => 'ajax-replace',
];

$list = elgg_list_entities($options);
if (empty($list)) {
	$options['count'] = elgg_count_entities($options);
	
	$content = elgg_view_no_results(elgg_echo('messages:nomessages'));
	$content .= elgg_view('page/components/list/out_of_bounds', $options);
} else {
	$content = elgg_view_form('messages/process', [
		'prevent_double_submit' => false,
	], [
		'folder' => 'sent',
		'list' => $list,
	]);
}

echo elgg_view_page($title, [
	'content' => $content,
	'show_owner_block_menu' => false,
	'filter_id' => 'messages',
	'filter_value' => 'sent',
]);
