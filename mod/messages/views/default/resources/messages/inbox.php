<?php
/**
 * Elgg messages inbox page
*/

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner instanceof ElggUser || !$page_owner->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

elgg_push_collection_breadcrumbs('object', 'messages', $page_owner);

elgg_register_title_button('messages', 'add', 'object', 'messages');

$title = elgg_echo('messages:user', [$page_owner->getDisplayName()]);

$list = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'messages',
	'metadata_name' => 'toId',
	'metadata_value' => elgg_get_page_owner_guid(),
	'owner_guid' => elgg_get_page_owner_guid(),
	'bulk_actions' => true,
]);

echo elgg_view_page($title, [
	'content' => elgg_view_form('messages/process', [], [
		'folder' => 'inbox',
		'list' => $list,
	]),
	'title' => elgg_echo('messages:inbox'),
	'show_owner_block_menu' => false,
]);
