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
		$title_str = elgg_get_excerpt($entity->description, 80);
	}

	$site = elgg_get_site_entity();

	// summary for site_notifications
	$summary = elgg_echo('likes:notifications:subject', array(
			$user->name,
			$title_str
		),
		$owner->language
	);
	
	// prevent long subjects in mail
	$title_str = elgg_get_excerpt($title_str, 80);
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
			'summary' => $summary,
		)
	);
}

system_message(elgg_echo("likes:likes"));

if (elgg_is_xhr()) {
	$num_of_likes = likes_count($entity);
	if ($num_of_likes == 1) {
		$likes_string = elgg_echo('likes:userlikedthis', array($num_of_likes));
	} else {
		$likes_string = elgg_echo('likes:userslikedthis', array($num_of_likes));
	}
	echo json_encode([
		'text' => $likes_string,
		'selector' => "[data-likes-guid={$entity->guid}]",
		'num_likes' => $num_of_likes,
	]);
}

// Forward back to the page where the user 'liked' the object
forward(REFERER);
