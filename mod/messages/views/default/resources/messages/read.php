<?php

$guid = (int) elgg_extract('guid', $vars);

/** @var \ElggMessage $entity */
$entity = elgg_entity_gatekeeper($guid, 'object', 'messages', true);

// mark the message as read
$entity->readYet = true;

$page_owner = elgg_get_page_owner_entity();

elgg_push_collection_breadcrumbs('object', 'messages', $page_owner);

$inbox = false;
if ($page_owner->guid === $entity->toId) {
	$inbox = true;
} else {
	elgg_push_breadcrumb(elgg_echo('messages:sent'), elgg_generate_url('collection:object:messages:sent', ['username' => $page_owner->username]));
}

$content = elgg_view_entity($entity, ['full_view' => true]);
if ($inbox) {
	$form_params = [
		'id' => 'messages-reply-form',
		'class' => 'hidden mtl',
		'action' => 'action/messages/send',
	];
	$body_params = ['message' => $entity];
	$content .= elgg_view_form('messages/reply', $form_params, $body_params);
	$from_user = get_user($entity->fromId);
	
	if ((elgg_get_logged_in_user_guid() === elgg_get_page_owner_guid()) && $from_user) {
		elgg_register_menu_item('title', [
			'name' => 'reply',
			'icon' => 'reply',
			'href' => '#messages-reply-form',
			'text' => elgg_echo('reply'),
			'link_class' => ['elgg-button', 'elgg-button-action', 'elgg-toggle'],
		]);
	}
}

echo elgg_view_page($entity->getDisplayName(), [
	'content' => $content,
	'entity' => $entity,
	'show_owner_block_menu' => false,
	'filter_id' => 'messages/view',
]);
