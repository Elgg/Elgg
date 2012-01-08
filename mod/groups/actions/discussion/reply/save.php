<?php
/**
 * Post a reply to discussion topic
 *
 */

// Get input
$entity_guid = (int) get_input('entity_guid');
$text = get_input('group_topic_post');
$annotation_id = (int) get_input('annotation_id');

// reply cannot be empty
if (empty($text)) {
	register_error(elgg_echo('grouppost:nopost'));
	forward(REFERER);
}

$topic = get_entity($entity_guid);
if (!$topic) {
	register_error(elgg_echo('grouppost:nopost'));
	forward(REFERER);
}

$user = elgg_get_logged_in_user_entity();

$group = $topic->getContainerEntity();
if (!$group->canWriteToContainer()) {
	register_error(elgg_echo('groups:notmember'));
	forward(REFERER);
}

// if editing a reply, make sure it's valid
if ($annotation_id) {
	$annotation = elgg_get_annotation_from_id($annotation_id);
	if (!$annotation->canEdit()) {
		register_error(elgg_echo('groups:notowner'));
		forward(REFERER);
	}

	$annotation->value = $text;
	if (!$annotation->save()) {
		system_message(elgg_echo('groups:forumpost:error'));
		forward(REFERER);
	}
	system_message(elgg_echo('groups:forumpost:edited'));
} else {
	// add the reply to the forum topic
	$reply_id = $topic->annotate('group_topic_post', $text, $topic->access_id, $user->guid);
	if ($reply_id == false) {
		system_message(elgg_echo('groupspost:failure'));
		forward(REFERER);
	}

	add_to_river('river/annotation/group_topic_post/reply', 'reply', $user->guid, $topic->guid, "", 0, $reply_id);
	system_message(elgg_echo('groupspost:success'));
}

forward(REFERER);
