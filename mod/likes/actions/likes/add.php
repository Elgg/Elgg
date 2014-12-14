<?php
/**
 * Elgg add like action
 *
 */

$entity_guid = (int) get_input('guid');

//check to see if the user has already liked the item
if (elgg_annotation_exists($entity_guid, 'likes')) {
	system_message(elgg_echo("likes:alreadyliked"));
	forward(REFERER);
}
// Let's see if we can get an entity with the specified GUID
$entity = get_entity($entity_guid);
if (!$entity) {
	register_error(elgg_echo("likes:notfound"));
	forward(REFERER);
}

// limit likes through a plugin hook (to prevent liking your own content for example)
if (!$entity->canAnnotate(0, 'likes')) {
	// plugins should register the error message to explain why liking isn't allowed
	forward(REFERER);
}

$user = elgg_get_logged_in_user_entity();
$annotation_id = create_annotation($entity->guid,
								'likes',
								"likes",
								"",
								$user->guid,
								$entity->access_id);

// tell user annotation didn't work if that is the case
if (!$annotation_id) {
	register_error(elgg_echo("likes:failure"));
	forward(REFERER);
}

// notify if poster wasn't owner
if ($entity->owner_guid != $user->guid) {
	$owner = $entity->getOwnerEntity();

	$annotation = elgg_get_annotation_from_id($annotation_id);

	$title_str = $entity->getDisplayName();
	if (!$title_str) {
		$title_str = elgg_get_excerpt($entity->description);
	}

	$site = elgg_get_site_entity();

	$subject = elgg_echo('likes:notifications:subject', array(
			$user->name,
			$title_str
		),
		$owner->language
	);

	$body = elgg_echo('likes:notifications:body', array(
			$owner->name,
			$user->name,
			$title_str,
			$site->name,
			$entity->getURL(),
			$user->getURL()
		),
		$owner->language
	);

	notify_user(
		$entity->owner_guid,
		$user->guid,
		$subject,
		$body,
		array(
			'action' => 'create',
			'object' => $annotation,
		)
	);
}

system_message(elgg_echo("likes:likes"));

// Forward back to the page where the user 'liked' the object
forward(REFERER);
