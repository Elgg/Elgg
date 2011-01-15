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
$page_owner = elgg_get_page_owner();

$title = $message->title;

$inbox = false;
if ($page_owner->getGUID() == $message->toId) {
	$inbox = true;
	elgg_push_breadcrumb(elgg_echo('messages:inbox'), 'pg/messages/inbox/' . $page_owner->username);
} else {
	elgg_push_breadcrumb(elgg_echo('messages:sent'), 'pg/messages/sent/' . $page_owner->username);
}
elgg_push_breadcrumb($title);

$buttons = '';
$content = elgg_view_entity($message, true);
if ($inbox) {
	$form_params = array(
		'internalid' => 'messages-reply-form',
		'class' => 'hidden',
		'action' => 'messages/send',
	);
	$body_params = array('message' => $message);
	$content .= elgg_view_form('messages/reply', $form_params, $body_params);

	if (get_loggedin_userid() == elgg_get_page_owner_guid()) {
		$buttons = elgg_view('output/url', array(
			'text' => elgg_echo('messages:answer'),
			'class' => 'elgg-action-button',
			'internalid' => 'messages-show-reply',
		));
	}
}

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'buttons' => $buttons,
));

echo elgg_view_page($title, $body);
