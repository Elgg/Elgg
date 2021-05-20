<?php
/**
* Compose a message
*/

$page_owner = elgg_get_logged_in_user_entity();
elgg_set_page_owner_guid($page_owner->getGUID());

elgg_push_collection_breadcrumbs('object', 'messages', $page_owner);

$params = messages_prepare_form_vars((int) get_input('send_to'));

echo elgg_view_page(elgg_echo('messages:add'), [
	'content' => elgg_view_form('messages/send', [], $params),
	'show_owner_block_menu' => false,
	'filter_id' => 'messages/edit',
]);
