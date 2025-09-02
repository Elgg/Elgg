<?php
/**
 * Elgg messages inbox page
 */

/* @var $page_owner \ElggUser */
$page_owner = elgg_get_page_owner_entity();

elgg_push_collection_breadcrumbs('object', 'messages', $page_owner);

elgg_register_title_button('add', 'object', 'messages');

$title = elgg_echo('messages:user', [$page_owner->getDisplayName()]);

$options = [
	'type' => 'object',
	'subtype' => 'messages',
	'metadata_name_value_pairs' => [
		'name' => 'toId',
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
	
	$content = elgg_view_no_results(elgg_echo('list:object:messages:no_results'));
	$content .= elgg_view('page/components/list/out_of_bounds', $options);
} else {
	$content = elgg_view_form('messages/process', [
		'prevent_double_submit' => false,
	], [
		'folder' => 'inbox',
		'list' => $list,
	]);
}

echo elgg_view_page($title, [
	'content' => $content,
	'title' => elgg_echo('messages:inbox'),
	'show_owner_block_menu' => false,
	'filter_id' => 'messages',
	'filter_value' => 'inbox',
]);
