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
function messages_send(string $subject, string $body, int $recipient_guid, int $sender_guid = 0, int $original_msg_guid = 0, bool $notify = true, bool $add_to_sent = true): int|false {
	if ($sender_guid == 0) {
		$sender_guid = (int) elgg_get_logged_in_user_guid();
	}

	$message_to = new ElggMessage();
	$message_sent = new ElggMessage();

	$message_to->owner_guid = $recipient_guid;
	$message_to->container_guid = $recipient_guid;
	$message_sent->owner_guid = $sender_guid;
	$message_sent->container_guid = $sender_guid;

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
	$saved = elgg_call(ELGG_IGNORE_ACCESS, function() use ($message_to) {
		return $message_to->save();
	});
	
	if (!$saved) {
		return false;
	}

	// Save the copy of the message that goes to the sender
	if ($add_to_sent) {
		elgg_call(ELGG_IGNORE_ACCESS, function() use ($message_sent) {
			$message_sent->save();
		});
	}

	// if the new message is a reply then create a relationship link between the new message
	// and the message it is in reply to
	if ($original_msg_guid) {
		$message_sent->addRelationship($original_msg_guid, 'reply');
	}

	if ($recipient_guid !== elgg_get_logged_in_user_guid() && $notify) {
		$message_contents = $body;
		
		$recipient = get_user($recipient_guid);
		$sender = get_user($sender_guid);
		if ($recipient instanceof \ElggUser && $sender instanceof \ElggUser) {
			$subject = elgg_echo('messages:email:subject', [], $recipient->getLanguage());
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
			], $recipient->getLanguage());
			
			$params = [
				'object' => $message_to,
				'action' => 'send',
				'url' => $message_to->getURL(),
			];
			notify_user($recipient_guid, $sender_guid, $subject, $body, $params);
		}
	}
	
	return $message_to->guid;
}

/**
 * Returns the unread messages in a user's inbox
 *
 * @param int      $user_guid GUID of user whose inbox we're counting (0 for logged-in user)
 * @param null|int $limit     Number of unread messages to return (default from settings)
 * @param int      $offset    Start at a defined offset (for listings)
 * @param bool     $count     Switch between entities array or count mode
 *
 * @return ElggMessage[]|int|false
 * @since 1.9
 */
function messages_get_unread(int $user_guid = 0, ?int $limit = null, int $offset = 0, bool $count = false) {
	if (!$user_guid) {
		$user_guid = elgg_get_logged_in_user_guid();
	}

	return elgg_get_entities([
		'type' => 'object',
		'subtype' => 'messages',
		'metadata_name_value_pairs' => [
			'toId' => $user_guid,
			'readYet' => 0,
		],
		'owner_guid' => $user_guid,
		'limit' => $limit ?: elgg_get_config('default_limit'),
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
function messages_count_unread(int $user_guid = 0): int {
	return (int) messages_get_unread($user_guid, 10, 0, true);
}
