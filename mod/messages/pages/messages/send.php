<?php
/**
* Compose a message
*
* @package ElggMessages
*/

gatekeeper();

$page_owner = elgg_get_logged_in_user_entity();
set_page_owner($page_owner->getGUID());

$title = elgg_echo('messages:add');

elgg_push_breadcrumb($title);

$params = messages_prepare_form_vars(get_input('send_to'));
$params['friends'] = $page_owner->getFriends();
$content = elgg_view_form('messages/send', array(), $params);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'buttons' => '',
));

echo elgg_view_page($title, $body);
