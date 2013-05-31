<?php
/**
 * Elgg add comment action
 *
 * @package Elgg.Core
 * @subpackage Comments
 */

$entity_guid = (int) get_input('entity_guid');
$comment_text = get_input('generic_comment');

if (empty($comment_text)) {
	register_error(elgg_echo("generic_comment:blank"));
	forward(REFERER);
}

// Let's see if we can get an entity with the specified GUID
$entity = get_entity($entity_guid);
if (!$entity) {
	register_error(elgg_echo("generic_comment:notfound"));
	forward(REFERER);
}

$user = elgg_get_logged_in_user_entity();

$comment = new ElggComment();
$comment->description = $comment_text;
$comment->owner_guid = $user->getGUID();
$comment->container_guid = $entity->getGUID();
$comment->access_id = $entity->access_id;
$guid = $comment->save();

if (!$guid) {
	register_error(elgg_echo("generic_comment:failure"));
	forward(REFERER);
}

// Notify if poster wasn't owner
if ($entity->owner_guid != $user->guid) {
	notify_user($entity->owner_guid,
		$user->guid,
		elgg_echo('generic_comment:email:subject'),
		elgg_echo('generic_comment:email:body', array(
			$entity->title,
			$user->name,
			$comment_text,
			$entity->getURL(),
			$user->name,
			$user->getURL()
		))
	);
}

// Add to river
elgg_create_river_item(array(
	'view' => 'river/object/comment/create',
	'action_type' => 'comment',
	'subject_guid' => $user->guid,
	'object_guid' => $guid,
	'target_guid' => $entity_guid,
));

system_message(elgg_echo('generic_comment:posted'));

// Forward back to the page where the action occurred
forward(REFERER);
