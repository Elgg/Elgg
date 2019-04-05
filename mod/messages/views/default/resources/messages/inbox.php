<?php
/**
 * Elgg messages inbox page
 *
 * @package ElggMessages
*/

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner instanceof ElggUser || !$page_owner->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

elgg_push_breadcrumb(elgg_echo('messages:inbox'));

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

$body_vars = [
	'folder' => 'inbox',
	'list' => $list,
];
$content = elgg_view_form('messages/process', [], $body_vars);

$body = elgg_view_layout('content', [
	'content' => $content,
	'title' => elgg_echo('messages:inbox'),
	'filter' => '',
	'show_owner_block_menu' => false,
]);

echo elgg_view_page($title, $body);
