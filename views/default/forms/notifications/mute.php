<?php
/**
 * Form to mute notifications about an entity
 *
 * @uses $vars['entity']    the entity to mute notifications for
 * @uses $vars['recipient'] the recipient of the notification
 * @uses $vars['actor']     the actor of the notification
 */

$entity = elgg_extract('entity', $vars);
$recipient = elgg_extract('recipient', $vars);
if (!$entity instanceof \ElggEntity || !$recipient instanceof \ElggUser) {
	return;
}

$get_language_key = function(\ElggEntity $entity, string $default_postfix) {
	$keys = [
		"notifications:mute:{$entity->type}:{$entity->subtype}",
		"notifications:mute:{$entity->type}",
		"notifications:mute:{$default_postfix}",
	];
	
	foreach ($keys as $key) {
		if (elgg_language_key_exists($key)) {
			return $key;
		}
	}
};

$checkboxes = '';
$mute_guids = [];

if ($entity instanceof \ElggComment) {
	$commented_entity = $entity->getContainerEntity();
	$mute_guids[] = $commented_entity->guid;
	
	$checkboxes .= elgg_view_field([
		'#type' => 'checkbox',
		'#label' => elgg_echo($get_language_key($commented_entity, 'entity'), [$commented_entity->getDisplayName()]),
		'name' => "mute[{$commented_entity->guid}]",
		'value' => 1,
		'switch' => true,
		'checked' => $commented_entity->hasMutedNotifications($recipient->guid),
	]);
} elseif (!elgg_is_empty($entity->getDisplayName())) {
	$mute_guids[] = $entity->guid;
	
	$checkboxes .= elgg_view_field([
		'#type' => 'checkbox',
		'#label' => elgg_echo($get_language_key($entity, 'entity'), [$entity->getDisplayName()]),
		'name' => "mute[{$entity->guid}]",
		'value' => 1,
		'switch' => true,
		'checked' => $entity->hasMutedNotifications($recipient->guid),
	]);
}

$container = ($entity instanceof ElggComment) ? $entity->getContainerEntity()->getContainerEntity() : $entity->getContainerEntity();
if (($container instanceof \ElggGroup || $container instanceof \ElggUser) && !in_array($container->guid, $mute_guids)) {
	$mute_guids[] = $container->guid;
	
	$checkboxes .= elgg_view_field([
		'#type' => 'checkbox',
		'#label' => elgg_echo($get_language_key($container, 'container'), [$container->getDisplayName()]),
		'name' => "mute[{$container->guid}]",
		'value' => 1,
		'switch' => true,
		'checked' => $container->hasMutedNotifications($recipient->guid),
	]);
}

$owner = $entity->getOwnerEntity();
if (($owner instanceof \ElggGroup || $owner instanceof \ElggUser) && !in_array($owner->guid, $mute_guids)) {
	$mute_guids[] = $owner->guid;
	
	$checkboxes .= elgg_view_field([
		'#type' => 'checkbox',
		'#label' => elgg_echo($get_language_key($owner, 'owner'), [$owner->getDisplayName()]),
		'name' => "mute[{$owner->guid}]",
		'value' => 1,
		'switch' => true,
		'checked' => $owner->hasMutedNotifications($recipient->guid),
	]);
}

$actor = elgg_extract('actor', $vars);
if (($actor instanceof \ElggGroup || $actor instanceof \ElggUser) && !in_array($actor->guid, $mute_guids)) {
	$mute_guids[] = $actor->guid;
	
	$checkboxes .= elgg_view_field([
		'#type' => 'checkbox',
		'#label' => elgg_echo($get_language_key($actor, 'actor'), [$actor->getDisplayName()]),
		'name' => "mute[{$actor->guid}]",
		'value' => 1,
		'switch' => true,
		'checked' => $actor->hasMutedNotifications($recipient->guid),
	]);
}

if (empty($checkboxes)) {
	// no content to show
	return;
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'entity_guid',
	'value' => $entity->guid,
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'recipient_guid',
	'value' => $recipient->guid,
]);

// protect form from manipulation between requests
$hmac = elgg_build_hmac([
	'entity_guid' => $entity->guid,
	'recipient_guid' => $recipient->guid,
]);
echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'hmac_token',
	'value' => $hmac->getToken(),
]);

echo elgg_view('output/longtext', [
	'value' => elgg_echo('notifications:mute:description'),
]);

echo $checkboxes;

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
