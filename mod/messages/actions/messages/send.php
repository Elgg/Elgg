<?php
/**
 * Ssend a message action
 */

$subject = elgg_strip_tags((string) get_input('subject'));
$body = get_input('body');
$recipients = (array) get_input('recipients');
$original_msg_guid = (int) get_input('original_guid');

if (empty($recipients)) {
	return elgg_error_response(elgg_echo('messages:user:blank'));
}

$recipient = (int) elgg_extract(0, $recipients);
if ($recipient == elgg_get_logged_in_user_guid()) {
	return elgg_error_response(elgg_echo('messages:user:self'));
}

$user = get_user($recipient);
if (!$user) {
	return elgg_error_response(elgg_echo('messages:user:nonexist'));
}

if ((bool) elgg_get_plugin_setting('friends_only', 'messages') && !$user->isFriendOf(elgg_get_logged_in_user_guid())) {
	return elgg_error_response(elgg_echo('messages:user:notfriend'));
}

// Make sure the message field, send to field and title are not blank
if (!$body || !$subject) {
	return elgg_error_response(elgg_echo('messages:blank'));
}

// Otherwise, 'send' the message
$result = messages_send($subject, $body, $user->guid, 0, $original_msg_guid);

// Save 'send' the message
if ($result === false) {
	return elgg_error_response(elgg_echo('messages:error'));
}

$forward = elgg_generate_url('collection:object:messages:owner', [
	'username' => elgg_get_logged_in_user_entity()->username,
]);
return elgg_ok_response([
	'sent_guid' => $result,
], elgg_echo('messages:posted'), $forward);
