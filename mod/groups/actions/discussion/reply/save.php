<?php
/**
 * Post a reply to discussion topic
 *
 */

// Get input
$topic_guid = (int) get_input('topic_guid');
$text = get_input('description');
$reply_guid = (int) get_input('guid');

// reply cannot be empty
if (empty($text)) {
	register_error(elgg_echo('grouppost:nopost'));
	forward(REFERER);
}

$topic = get_entity($topic_guid);
if (!elgg_instanceof($topic, 'object', 'groupforumtopic')) {
	register_error(elgg_echo('grouppost:nopost'));
	forward(REFERER);
}

$user = elgg_get_logged_in_user_entity();

$group = $topic->getContainerEntity();
if (!$group->canWriteToContainer()) {
	register_error(elgg_echo('groups:notmember'));
	forward(REFERER);
}

if ($reply_guid) {
	$reply = get_entity($reply_guid);
	if (!$reply->canEdit()) {
		register_error(elgg_echo('groups:notowner'));
		forward(REFERER);
	}

	$reply->description = $text;

	if (!$reply->save()) {
		register_error(elgg_echo('groups:forumpost:error'));
		forward(REFERER);
	}

	system_message(elgg_echo('groups:forumpost:edited'));
} else {
	// add the reply to the forum topic
	$reply = new ElggGroupforumReply();
	$reply->description = $text;
	$reply->access_id = $topic->access_id;
	$reply->container_guid = $topic->getGUID();
	$reply->owner_guid = $user->getGUID();

	$reply_guid = $reply->save();

	if ($reply_guid == false) {
		register_error(elgg_echo('groupspost:failure'));
		forward(REFERER);
	}

	elgg_create_river_item(array(
		'view' => 'river/object/groupforumreply/create',
		'action_type' => 'reply',
		'subject_guid' => $user->guid,
		'object_guid' => $reply->guid,
		'target_guid' => $topic->guid,
	));

	system_message(elgg_echo('groupspost:success'));
}

forward(REFERER);
