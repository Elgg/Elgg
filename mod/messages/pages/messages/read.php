<?php
/**
* Read a message page
*
* @package ElggMessages
*/

gatekeeper();

$message = get_entity(get_input('guid'));
if (!$message) {
	forward();
}

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

$buttons = '';
$content = elgg_view_entity($message, true);
if ($inbox) {
	$form_params = array(
		'id' => 'messages-reply-form',
		'class' => 'hidden mtl',
		'action' => 'action/messages/send',
	);
	$body_params = array('message' => $message);
	$content .= elgg_view_form('messages/reply', $form_params, $body_params);

	if (elgg_get_logged_in_user_guid() == elgg_get_page_owner_guid()) {
		$buttons = elgg_view('output/url', array(
			'text' => elgg_echo('messages:answer'),
			'class' => 'elgg-button elgg-button-action',
			'id' => 'messages-show-reply',
		));
		$buttons = "<ul class=\"elgg-menu elgg-menu-title\"><li>$buttons</li></ul>";
	}
}

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'buttons' => $buttons,
));

echo elgg_view_page($title, $body);
