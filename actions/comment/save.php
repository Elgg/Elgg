<?php
/**
 * Action for adding and editing comments
 */

use Elgg\Values;

$entity_guid = (int) get_input('entity_guid', 0, false);
$comment_guid = (int) get_input('comment_guid', 0, false);
$comment_text = get_input('generic_comment');

if (empty($comment_text)) {
	return elgg_error_response(elgg_echo('generic_comment:blank'));
}

if ($comment_guid) {
	// Edit an existing comment
	$comment = get_entity($comment_guid);
	if (!$comment instanceof \ElggComment) {
		return elgg_error_response(elgg_echo('generic_comment:notfound'));
	}
	
	if (!$comment->canEdit()) {
		return elgg_error_response(elgg_echo('actionunauthorized'));
	}

	$comment->description = $comment_text;
	if (!$comment->save()) {
		return elgg_error_response(elgg_echo('generic_comment:failure'));
	}
	
	$entity = $comment->getContainerEntity();
	$success_message = elgg_echo('generic_comment:updated');
} else {
	// Create a new comment on the target entity
	$entity = get_entity($entity_guid);
	if (!$entity instanceof \ElggEntity) {
		return elgg_error_response(elgg_echo('generic_comment:notfound'));
	}
	
	if (!$entity->canComment()) {
		return elgg_error_response(elgg_echo('actionunauthorized'));
	}

	$comment = new \ElggComment();
	$comment->description = $comment_text;
	
	if ($entity instanceof \ElggComment) {
		$comment->level = $entity->getLevel() + 1;
		$comment->parent_guid = $entity->guid;
		$comment->thread_guid = $entity->getThreadGUID();
		
		// make sure comment is contained in the content
		$entity = $entity->getContainerEntity();
	}

	$comment->container_guid = $entity->guid;
	$comment->access_id = $entity->access_id;
		
	if (!$comment->save()) {
		return elgg_error_response(elgg_echo('generic_comment:failure'));
	}

	// only river for top level comments
	if ($comment->getLevel() === 1) {
		// Add to river
		elgg_create_river_item([
			'view' => 'river/object/comment/create',
			'action_type' => 'comment',
			'object_guid' => $comment->guid,
			'target_guid' => $entity->guid,
		]);
	}
	
	$success_message = elgg_echo('generic_comment:posted');
}

$comment_count = $entity->countComments();

$result = [
	'guid' => $comment->guid,
	'output' => elgg_view_entity($comment),
	'container_guid' => $entity->guid,
	'container_comment_count' => $comment_count,
	'container_comment_badge_text' => $comment_count ? Values::shortFormatOutput($comment_count, 1) : null,
];

return elgg_ok_response($result, $success_message, $comment->getURL());
