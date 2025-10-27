<?php
/**
 * Offer a page where a user can mute notifications about an
 * - entity
 * - container
 * - owner
 * - actor
 */

$entity_guid = (int) elgg_extract('entity_guid', $vars);
if (!elgg_entity_exists($entity_guid)) {
	throw new \Elgg\Exceptions\Http\EntityNotFoundException();
}

$recipient_guid = (int) elgg_extract('recipient_guid', $vars);
/** @var \ElggUser $recipient */
$recipient = elgg_entity_gatekeeper($recipient_guid, 'user');

$actor_guid = (int) get_input('actor_guid', elgg_extract('actor_guid', $vars));

elgg_set_page_owner_guid($recipient->guid);
elgg()->translator->setCurrentLanguage($recipient->getLanguage());

// using ignored access to be abe to show non public content
$content = elgg_call(ELGG_IGNORE_ACCESS, function() use ($entity_guid, $recipient, $actor_guid) {
	$entity = get_entity($entity_guid);
	$actor = get_entity($actor_guid);
	
	return elgg_view_form('notifications/mute', [], [
		'entity' => $entity,
		'recipient' => $recipient,
		'actor' => $actor,
	]);
});

if (empty($content)) {
	throw new \Elgg\Exceptions\Http\PageNotFoundException(elgg_echo('notifications:mute:error:content'));
}

echo elgg_view_page(elgg_echo('notifications:mute:title'), [
	'content' => $content,
	'filter_id' => 'notifications',
	'filter_value' => 'mute',
	'show_owner_block_menu' => false,
]);
