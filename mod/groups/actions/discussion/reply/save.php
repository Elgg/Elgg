<?php
/**
 * Save a discussion reply
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

if ($topic_guid) {
	$topic = get_entity($topic_guid);
	if (!elgg_instanceof($topic, 'object', 'groupforumtopic')) {
		register_error(elgg_echo('grouppost:nopost'));
		forward(REFERER);
	}

	$group = $topic->getContainerEntity();
	if (!$group->canWriteToContainer()) {
		register_error(elgg_echo('groups:notmember'));
		forward(REFERER);
	}
}

$user = elgg_get_logged_in_user_entity();
if ($reply_guid) {
	$reply = get_entity($reply_guid);

	if (!elgg_instanceof($reply, 'object', 'discussion_reply', 'ElggDiscussionReply')) {
		register_error(elgg_echo('discussion:reply:error:notfound'));
		forward(REFERER);
	}

	if (!$reply->canEdit()) {
		register_error(elgg_echo('groups:notowner'));
		forward(REFERER);
	}

	$reply->description = $text;

	if ($reply->save()) {
		system_message(elgg_echo('groups:forumpost:edited'));
	} else {
		register_error(elgg_echo('groups:forumpost:error'));
	}
} else {
	// add the reply to the forum topic
	$reply = new ElggDiscussionReply();
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
		'view' => 'river/object/discussion_reply/create',
		'action_type' => 'reply',
		'subject_guid' => $user->guid,
		'object_guid' => $reply->guid,
		'target_guid' => $topic->guid,
	));

	system_message(elgg_echo('groupspost:success'));
}

forward(REFERER);
