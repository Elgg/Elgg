<?php
/**
* Compose a message
*
* @package ElggMessages
*/

$page_owner = elgg_get_logged_in_user_entity();
elgg_set_page_owner_guid($page_owner->getGUID());

$title = elgg_echo('messages:add');

elgg_push_breadcrumb(elgg_echo('messages'), 'messages/inbox/' . $page_owner->username);
elgg_push_breadcrumb($title);

$params = messages_prepare_form_vars((int) get_input('send_to'));
$content = elgg_view_form('messages/send', [], $params);

$body = elgg_view_layout('content', [
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'show_owner_block_menu' => false,
]);

echo elgg_view_page($title, $body);
