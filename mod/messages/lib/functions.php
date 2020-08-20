<?php
/**
 * Holds helper functions for messages plugin
 */

/**
 * Send an internal message
 *
 * @param string $subject           The subject line of the message
 * @param string $body              The body of the mesage
 * @param int    $recipient_guid    The GUID of the user to send to
 * @param int    $sender_guid       Optionally, the GUID of the user to send from
 * @param int    $original_msg_guid The GUID of the message to reply from (default: none)
 * @param bool   $notify            Send a notification (default: true)
 * @param bool   $add_to_sent       If true (default), will add a message to the sender's 'sent' tray
 *
 * @return false|int
 */
function messages_send($subject, $body, $recipient_guid, $sender_guid = 0, $original_msg_guid = 0, $notify = true, $add_to_sent = true) {

	// @todo remove globals
	global $messagesendflag;
	$messagesendflag = 1;

	// If $sender_guid == 0, set to current user
	if ($sender_guid == 0) {
		$sender_guid = (int) elgg_get_logged_in_user_guid();
	}

	$message_to = new ElggMessage();
	$message_sent = new ElggMessage();

	$message_to->owner_guid = $recipient_guid;
	$message_to->container_guid = $recipient_guid;
	$message_sent->owner_guid = $sender_guid;
	$message_sent->container_guid = $sender_guid;

	$message_to->access_id = ACCESS_PUBLIC;
	$message_sent->access_id = ACCESS_PUBLIC;

	$message_to->title = $subject;
	$message_to->description = $body;

	$message_sent->title = $subject;
	$message_sent->description = $body;

	$message_to->toId = $recipient_guid; // the user receiving the message
	$message_to->fromId = $sender_guid; // the user receiving the message
	$message_to->readYet = 0; // this is a toggle between 0 / 1 (1 = read)
	$message_to->hiddenFrom = 0; // this is used when a user deletes a message in their sentbox, it is a flag
	$message_to->hiddenTo = 0; // this is used when a user deletes a message in their inbox

	$message_sent->toId = $recipient_guid; // the user receiving the message
	$message_sent->fromId = $sender_guid; // the user receiving the message
	$message_sent->readYet = 0; // this is a toggle between 0 / 1 (1 = read)
	$message_sent->hiddenFrom = 0; // this is used when a user deletes a message in their sentbox, it is a flag
	$message_sent->hiddenTo = 0; // this is used when a user deletes a message in their inbox

	// Save the copy of the message that goes to the recipient
	if (!$message_to->save()) {
		return false;
	}

	// Save the copy of the message that goes to the sender
	if ($add_to_sent) {
		$message_sent->save();
	}

	$message_to->access_id = ACCESS_PRIVATE;
	$message_to->save();

	if ($add_to_sent) {
		$message_sent->access_id = ACCESS_PRIVATE;
		$message_sent->save();
	}

	// if the new message is a reply then create a relationship link between the new message
	// and the message it is in reply to
	if ($original_msg_guid) {
		add_entity_relationship($message_sent->guid, "reply", $original_msg_guid);
	}

	if (($recipient_guid != elgg_get_logged_in_user_guid()) && $notify) {
		$message_contents = $body;
		$recipient = get_user($recipient_guid);
		$sender = get_user($sender_guid);
		
		$subject = elgg_echo('messages:email:subject', [], $recipient->language);
		$body = elgg_echo('messages:email:body', [
				$sender->getDisplayName(),
				$message_contents,
				elgg_generate_url('collection:object:messages:owner', [
					'username' => $recipient->username,
				]),
				$sender->getDisplayName(),
				elgg_generate_url('add:object:messages', [
					'send_to' => $sender_guid,
				]),
			],
			$recipient->language
		);

		$params = [
			'object' => $message_to,
			'action' => 'send',
			'url' => $message_to->getURL(),
		];
		notify_user($recipient_guid, $sender_guid, $subject, $body, $params);
	}

	$messagesendflag = 0;
	
	return $message_to->guid;
}

/**
 * Returns the unread messages in a user's inbox
 *
 * @param int  $user_guid GUID of user whose inbox we're counting (0 for logged in user)
 * @param int  $limit     Number of unread messages to return (default from settings)
 * @param int  $offset    Start at a defined offset (for listings)
 * @param bool $count     Switch between entities array or count mode
 *
 * @return ElggMessage[]|int|false
 * @since 1.9
 */
function messages_get_unread($user_guid = 0, $limit = null, $offset = 0, $count = false) {
	if (!$user_guid) {
		$user_guid = elgg_get_logged_in_user_guid();
	}

	return elgg_get_entities([
		'type' => 'object',
		'subtype' => 'messages',
		'metadata_name_value_pairs' => [
			'toId' => (int) $user_guid,
			'readYet' => 0,
		],
		'owner_guid' => (int) $user_guid,
		'limit' => $limit ? : elgg_get_config('default_limit'),
		'offset' => $offset,
		'count' => $count,
		'distinct' => false,
	]);
}

/**
 * Count the unread messages in a user's inbox
 *
 * @param int $user_guid GUID of user whose inbox we're counting (0 for logged in user)
 *
 * @return int
 */
function messages_count_unread($user_guid = 0) {
	return (int) messages_get_unread($user_guid, 10, 0, true);
}

/**
 * Prepare the compose form variables
 *
 * @param int $recipient_guid new message recipient
 *
 * @return array
 */
function messages_prepare_form_vars($recipient_guid = 0) {

	$recipients = [];
	$recipient = get_user($recipient_guid);
	if (!empty($recipient)) {
		$recipients[] = $recipient->getGUID();
	}

	// input names => defaults
	$values = [
		'subject' => elgg_get_sticky_value('messages', 'subject', ''),
		'body' => elgg_get_sticky_value('messages', 'body', ''),
		'recipients' => elgg_get_sticky_value('messages', 'recipients', $recipients),
	];

	elgg_clear_sticky_form('messages');

	return $values;
}
