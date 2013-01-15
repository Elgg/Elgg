<?php
/**
* Read a message page
*
* @package ElggMessages
*/

gatekeeper();

$message = get_entity(get_input('guid'));
if (!$message || !elgg_instanceof($message, "object", "messages")) {
	forward('messages/inbox/' . elgg_get_logged_in_user_entity()->username);
}

// mark the message as read
$message->readYet = true;

elgg_set_page_owner_guid($message->getOwnerGUID());
$page_owner = elgg_get_page_owner_entity();

$title = $message->title;

$inbox = false;
if ($page_owner->getGUID() == $message->toId) {
	$inbox = true;
	elgg_push_breadcrumb(elgg_echo('messages:inbox'), 'messages/inbox/' . $page_owner->username);
} else {
	elgg_push_breadcrumb(elgg_echo('messages:sent'), 'messages/sent/' . $page_owner->username);
}
elgg_push_breadcrumb($title);

$content = elgg_view_entity($message, array('full_view' => true));
if ($inbox) {
	$form_params = array(
		'id' => 'messages-reply-form',
		'class' => 'hidden mtl',
		'action' => 'action/messages/send',
	);
	$body_params = array('message' => $message);
	$content .= elgg_view_form('messages/reply', $form_params, $body_params);
	$from_user = get_user($message->fromId);
	
	if ((elgg_get_logged_in_user_guid() == elgg_get_page_owner_guid()) && $from_user) {
		elgg_register_menu_item('title', array(
			'name' => 'reply',
			'href' => '#messages-reply-form',
			'text' => elgg_echo('messages:answer'),
			'link_class' => 'elgg-button elgg-button-action',
			'rel' => 'toggle',
		));
	}
}

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);
