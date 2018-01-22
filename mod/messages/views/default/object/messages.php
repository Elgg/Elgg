<?php
/**
 * File renderer.
 *
 * @package ElggFile
 */

$full = elgg_extract('full_view', $vars, false);
$entity = elgg_extract('entity', $vars, false);

if (!$entity instanceof ElggMessage) {
	return;
}

$icon = '';
$byline = '';
$user_link = elgg_echo('messages:deleted_sender');

$class = ['message'];
if ($entity->toId == elgg_get_page_owner_guid()) {
	// received
	$user = get_user($entity->fromId);
	if ($user) {
		$icon = elgg_view_entity_icon($user, 'small');
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
	$user = get_user($entity->toId);

	if ($user) {
		$icon = elgg_view_entity_icon($user, 'small');
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

if ($full) {
	$body = elgg_view('output/longtext', [
		'value' => $entity->description,
	]);

	$params = [
		'entity' => $entity,
		'title' => false,
		'byline' => $byline,
		'show_social_menu' => false,
		'access' => false,
	];
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	echo elgg_view('object/elements/full', [
		'entity' => $entity,
		'summary' => $summary,
		'icon' => $icon,
		'body' => $body,
		'class' => $class,
		'show_responses' => false,
	]);
	
	return;
}

$body .= elgg_view('output/longtext', [
	'value' => elgg_get_excerpt($entity->description),
]);

$params = [
	'entity' => $entity,
	'class' => $class,
	'access' => false,
	'byline' => $byline,
	'show_social_menu' => false,
	'content' => $body,
];
$params = $params + $vars;
$summary = elgg_view('object/elements/summary', $params);

$bulk_actions = (bool) elgg_extract('bulk_actions', $vars, false);
if (!$bulk_actions) {
	echo elgg_view_image_block($icon, $summary, ['class' => $class]);
	return;
}

$checkbox = elgg_view('input/checkbox', [
	'name' => 'message_id[]',
	'value' => $entity->guid,
	'default' => false,
]);

$entity_listing = elgg_view_image_block($icon, $summary, ['class' => $class]);

echo elgg_view_image_block($checkbox, $entity_listing);
