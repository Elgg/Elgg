<?php
/**
 * Post a reply to discussion topic
 *
 */

// Get input
$entity_guid = (int) get_input('entity_guid');
$text = get_input('project_topic_post');
$annotation_id = (int) get_input('annotation_id');

// reply cannot be empty
if (empty($text)) {
	register_error(elgg_echo('projectpost:nopost'));
	forward(REFERER);
}

$topic = get_entity($entity_guid);
if (!$topic) {
	register_error(elgg_echo('projectpost:nopost'));
	forward(REFERER);
}

$user = elgg_get_logged_in_user_entity();

$project = $topic->getContainerEntity();
if (!$project->canWriteToContainer()) {
	register_error(elgg_echo('projects:notmember'));
	forward(REFERER);
}

// if editing a reply, make sure it's valid
if ($annotation_id) {
	$annotation = elgg_get_annotation_from_id($annotation_id);
	if (!$annotation->canEdit()) {
		register_error(elgg_echo('projects:notowner'));
		forward(REFERER);
	}

	$annotation->value = $text;
	if (!$annotation->save()) {
		system_message(elgg_echo('projects:forumpost:error'));
		forward(REFERER);
	}
	system_message(elgg_echo('projects:forumpost:edited'));
} else {
	// add the reply to the forum topic
	$reply_id = $topic->annotate('project_topic_post', $text, $topic->access_id, $user->guid);
	if ($reply_id == false) {
		system_message(elgg_echo('projectspost:failure'));
		forward(REFERER);
	}

	add_to_river('river/annotation/project_topic_post/reply', 'reply', $user->guid, $topic->guid, "", 0, $reply_id);
	system_message(elgg_echo('projectspost:success'));
}

forward(REFERER);
