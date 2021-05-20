<?php
/**
* Read a message page
*/

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'messages');

$message = get_entity($guid);
/* @var ElggMessage $message */

// mark the message as read
$message->readYet = true;

elgg_set_page_owner_guid($message->getOwnerGUID());
$page_owner = elgg_get_page_owner_entity();

elgg_push_collection_breadcrumbs('object', 'messages', $page_owner);

$inbox = false;
if ($page_owner->getGUID() == $message->toId) {
	$inbox = true;
} else {
	elgg_push_breadcrumb(elgg_echo('messages:sent'), elgg_generate_url('collection:object:messages:sent', ['username' => $page_owner->username]));
}

$content = elgg_view_entity($message, ['full_view' => true]);
if ($inbox) {
	$form_params = [
		'id' => 'messages-reply-form',
		'class' => 'hidden mtl',
		'action' => 'action/messages/send',
	];
	$body_params = ['message' => $message];
	$content .= elgg_view_form('messages/reply', $form_params, $body_params);
	$from_user = get_user($message->fromId);
	
	if ((elgg_get_logged_in_user_guid() == elgg_get_page_owner_guid()) && $from_user) {
		elgg_register_menu_item('title', [
			'name' => 'reply',
			'href' => '#messages-reply-form',
			'text' => elgg_echo('reply'),
			'link_class' => 'elgg-button elgg-button-action',
			'rel' => 'toggle',
		]);
	}
}

echo elgg_view_page($message->getDisplayName(), [
	'content' => $content,
	'entity' => $message,
	'show_owner_block_menu' => false,
	'filter_id' => 'messages/view',
]);
