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
	return elgg_error_response(elgg_echo('discussion:reply:missing'));
}

if ($topic_guid) {
	$topic = get_entity($topic_guid);
	if (!elgg_instanceof($topic, 'object', 'discussion')) {
		return elgg_error_response(elgg_echo('discussion:reply:topic_not_found'));
	}

	if (!$topic->canWriteToContainer(0, 'object', 'discussion_reply')) {
		return elgg_error_response(elgg_echo('discussion:reply:error:permissions'));
	}
}

$forward_url = false;
// return to activity page if posted from there
if (!empty($_SERVER['HTTP_REFERER'])) {
	// don't redirect to URLs from client without verifying within site
	$site_url = preg_quote(elgg_get_site_url(), '~');
	if (preg_match("~^{$site_url}activity(/|\\z)~", $_SERVER['HTTP_REFERER'], $m)) {
		$forward_url = "{$m[0]}";
	}
}

if ($reply_guid) {
	$reply = get_entity($reply_guid);

	if (!elgg_instanceof($reply, 'object', 'discussion_reply')) {
		return elgg_error_response(elgg_echo('discussion:reply:error:notfound'));
	}

	if (!$reply->canEdit()) {
		return elgg_error_response(elgg_echo('discussion:reply:error:cannot_edit'));
	}

	$reply->description = $text;

	if (!$reply->save()) {
		return elgg_error_response(elgg_echo('discussion:reply:error'));
	}
	
	if ($forward_url === false) {
		$forward_url = $reply->getURL();
	} else {
		$forward_url .= "#elgg-object-{$reply->guid}";
	}
	
	return elgg_ok_response('', elgg_echo('discussion:reply:edited'), $forward_url);
}
	
// add the reply to the forum topic
$reply = new ElggDiscussionReply();
$reply->description = $text;
$reply->access_id = $topic->access_id;
$reply->container_guid = $topic->getGUID();
$reply->owner_guid = elgg_get_logged_in_user_guid();

$reply_guid = $reply->save();

if ($reply_guid == false) {
	return elgg_error_response(elgg_echo('discussion:post:failure'));
}

elgg_create_river_item([
	'view' => 'river/object/discussion_reply/create',
	'action_type' => 'reply',
	'object_guid' => $reply->guid,
	'target_guid' => $topic->guid,
]);

if ($forward_url === false) {
	$forward_url = $reply->getURL();
} else {
	$forward_url .= "#elgg-object-{$reply->guid}";
}

return elgg_ok_response('', elgg_echo('discussion:post:success'), $forward_url);
