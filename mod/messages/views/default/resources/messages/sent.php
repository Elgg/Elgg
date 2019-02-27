<?php
/**
* Elgg sent messages page
*
* @package ElggMessages
*/

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner instanceof ElggUser || !$page_owner->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

elgg_push_breadcrumb(elgg_echo('messages'), 'messages/inbox/' . $page_owner->username);
elgg_push_breadcrumb(elgg_echo('messages:sent'));

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

$body_vars = [
	'folder' => 'sent',
	'list' => $list,
];
$content = elgg_view_form('messages/process', [], $body_vars);

$body = elgg_view_layout('content', [
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'show_owner_block_menu' => false,
]);

echo elgg_view_page($title, $body);
