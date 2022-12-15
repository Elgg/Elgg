<?php
/**
 * Compose a message
 */

$page_owner = elgg_get_logged_in_user_entity();
elgg_set_page_owner_guid($page_owner->guid);

elgg_push_collection_breadcrumbs('object', 'messages', $page_owner);

echo elgg_view_page(elgg_echo('messages:add'), [
	'content' => elgg_view_form('messages/send', ['sticky_enabled' => true], [
		'recipients' => [
			(int) get_input('send_to'),
		]
	]),
	'show_owner_block_menu' => false,
	'filter_id' => 'messages/edit',
]);
