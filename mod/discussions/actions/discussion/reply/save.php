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
	register_error(elgg_echo('discussion:reply:missing'));
	forward(REFERER);
}

if ($topic_guid) {
	$topic = get_entity($topic_guid);
	if (!elgg_instanceof($topic, 'object', 'discussion')) {
		register_error(elgg_echo('discussion:reply:topic_not_found'));
		forward(REFERER);
	}

	if (!$topic->canWriteToContainer(0, 'object', 'discussion_reply')) {
		register_error(elgg_echo('discussion:reply:error:permissions'));
		forward(REFERER);
	}
}

$user = elgg_get_logged_in_user_entity();
if ($reply_guid) {
	$reply = get_entity($reply_guid);

	if (!elgg_instanceof($reply, 'object', 'discussion_reply')) {
		register_error(elgg_echo('discussion:reply:error:notfound'));
		forward(REFERER);
	}

	if (!$reply->canEdit()) {
		register_error(elgg_echo('discussion:reply:error:cannot_edit'));
		forward(REFERER);
	}

	$reply->description = $text;

	if ($reply->save()) {
		system_message(elgg_echo('discussion:reply:edited'));
	} else {
		register_error(elgg_echo('discussion:reply:error'));
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
		register_error(elgg_echo('discussion:post:failure'));
		forward(REFERER);
	}

	elgg_create_river_item(array(
		'view' => 'river/object/discussion_reply/create',
		'action_type' => 'reply',
		'subject_guid' => $user->guid,
		'object_guid' => $reply->guid,
		'target_guid' => $topic->guid,
	));

	system_message(elgg_echo('discussion:post:success'));
}

// return to activity page if posted from there
if (!empty($_SERVER['HTTP_REFERER'])) {
	// don't redirect to URLs from client without verifying within site
	$site_url = preg_quote(elgg_get_site_url(), '~');
	if (preg_match("~^{$site_url}activity(/|\\z)~", $_SERVER['HTTP_REFERER'], $m)) {
		forward("{$m[0]}#elgg-object-{$reply->guid}");
	}
}

forward($reply->getURL());
