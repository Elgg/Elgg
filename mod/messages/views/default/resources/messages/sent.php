<?php
/**
* Elgg sent messages page
*/

use Elgg\Exceptions\Http\EntityPermissionsException;

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner instanceof ElggUser || !$page_owner->canEdit()) {
	throw new EntityPermissionsException();
}

elgg_push_collection_breadcrumbs('object', 'messages', $page_owner);

elgg_register_title_button('messages', 'add', 'object', 'messages');

$title = elgg_echo('messages:sentmessages', [$page_owner->getDisplayName()]);

$list = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'messages',
	'metadata_name' => 'fromId',
	'metadata_value' => elgg_get_page_owner_guid(),
	'owner_guid' => elgg_get_page_owner_guid(),
	'full_view' => false,
	'bulk_actions' => true
]);

echo elgg_view_page($title, [
	'content' => elgg_view_form('messages/process', [], [
		'folder' => 'sent',
		'list' => $list,
	]),
	'show_owner_block_menu' => false,
	'filter_id' => 'messages',
	'filter_value' => 'sent',
]);
