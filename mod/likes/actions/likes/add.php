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

// limit likes through an event (to prevent liking your own content for example)
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

return elgg_ok_response('', elgg_echo('likes:likes'));
