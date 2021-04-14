<?php
/**
 * Elgg add like action
 */

$entity_guid = (int) get_input('guid');

// Let's see if we can get an entity with the specified GUID
$entity = get_entity($entity_guid);
if (!$entity) {
	return elgg_error_response(elgg_echo('likes:notfound'));
}

//check to see if the user has already liked the item
if (elgg_annotation_exists($entity_guid, 'likes')) {
	return elgg_ok_response('', elgg_echo('likes:alreadyliked'));
}

// limit likes through a plugin hook (to prevent liking your own content for example)
if (!$entity->canAnnotate(0, 'likes')) {
	// plugins should register the error message to explain why liking isn't allowed
	return elgg_error_response();
}

$user = elgg_get_logged_in_user_entity();

$annotation_id = $entity->annotate('likes', 'likes', ACCESS_PUBLIC);

// tell user annotation didn't work if that is the case
if (!$annotation_id) {
	return elgg_error_response(elgg_echo('likes:failure'));
}

if ($entity->owner_guid === $user->guid) {
	return elgg_ok_response('', elgg_echo('likes:likes'));
}

// @todo move this notification to a notification event handler
// notify if poster wasn't owner
$owner = $entity->getOwnerEntity();

$annotation = elgg_get_annotation_from_id($annotation_id);

$title_str = $entity->getDisplayName();
if (!$title_str) {
	$title_str = elgg_get_excerpt($entity->description, 80);
}

$site = elgg_get_site_entity();

// summary for site_notifications
$summary = elgg_echo('likes:notifications:subject', [
		$user->getDisplayName(),
		$title_str,
	],
	$owner->language
);

// prevent long subjects in mail
$title_str = elgg_get_excerpt($title_str, 80);
$subject = elgg_echo('likes:notifications:subject', [
		$user->getDisplayName(),
		$title_str,
	],
	$owner->language
);

$body = elgg_echo('likes:notifications:body', [
		$user->getDisplayName(),
		$title_str,
		$site->getDisplayName(),
		$entity->getURL(),
		$user->getURL(),
	],
	$owner->language
);

notify_user(
	$entity->owner_guid,
	$user->guid,
	$subject,
	$body,
	[
		'action' => 'create',
		'object' => $annotation,
		'summary' => $summary,
		'url' => $entity->getURL(),
	]
);

return elgg_ok_response('', elgg_echo('likes:likes'));
