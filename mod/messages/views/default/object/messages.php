<?php
/**
 * File renderer.
 *
 * @uses $vars['entity'] ELggMessage to show
 */

$entity = elgg_extract('entity', $vars, false);
if (!$entity instanceof ElggMessage) {
	return;
}

$icon_entity = null;
$icon = '';
$byline = '';
$user_link = elgg_echo('messages:deleted_sender');

$class = ['message'];
if ($entity->toId == elgg_get_page_owner_guid()) {
	// received
	$user = $entity->getSender();
	if ($user) {
		$icon_entity = $user;
		$user_link = elgg_view('output/url', [
			'href' => elgg_generate_url('add:object:messages', [
				'send_to' => $user->guid,
			]),
			'text' => $user->getDisplayName(),
			'is_trusted' => true,
		]);
		
		$byline = elgg_echo('email:from') . ' ' . $user_link;
	}

	$class[] = $entity->readYet ? 'read': 'unread';
} else {
	// sent
	$user = $entity->getRecipient();
	if ($user) {
		$icon_entity = $user;
		$user_link = elgg_view('output/url', [
			'href' => elgg_generate_url('add:object:messages', [
				'send_to' => $user->guid,
			]),
			'text' => $user->getDisplayName(),
			'is_trusted' => true,
		]);

		$byline = elgg_echo('email:to') . ' ' . $user_link;
	}

	$class[] = 'read';
}

if (elgg_extract('full_view', $vars)) {
	$body = elgg_view('output/longtext', [
		'value' => $entity->description,
	]);

	$params = [
		'byline' => $byline,
		'show_social_menu' => false,
		'access' => false,
		'show_summary' => true,
		'icon_entity' => $icon_entity,
		'body' => $body,
		'class' => $class,
		'show_responses' => false,
	];
	$params = $params + $vars;
	
	echo elgg_view('object/elements/full', $params);
	return;
}

$body = elgg_view('output/longtext', [
	'value' => elgg_get_excerpt($entity->description),
]);

$params = [
	'class' => $class,
	'access' => false,
	'byline' => $byline,
	'show_social_menu' => false,
	'content' => $body,
	'icon_entity' => $icon_entity,
];
$params = $params + $vars;
$summary = elgg_view('object/elements/summary', $params);

$bulk_actions = (bool) elgg_extract('bulk_actions', $vars, false);
if (!$bulk_actions) {
	echo $summary;
	return;
}

$checkbox = elgg_view('input/checkbox', [
	'name' => 'message_id[]',
	'value' => $entity->guid,
	'default' => false,
]);

echo elgg_view_image_block($checkbox, $summary);
