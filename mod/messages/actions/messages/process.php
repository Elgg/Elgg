<?php
/**
 * Process a set of messages
 */

$message_ids = get_input('message_id', array());

if (!$message_ids) {
	register_error(elgg_echo('messages:error:messages_not_selected'));
	forward(REFERER);
}

$delete_flag = get_input('delete', false);
$read_flag = get_input('read', false);

if ($delete_flag) {
	$success_msg = elgg_echo('messages:success:delete');
	foreach ($message_ids as $guid) {
		$message = get_entity($guid);
		if ($message && $message->getSubtype() == 'messages' && $message->canEdit()) {
			$message->delete();
		}
	}
} else {
	$success_msg = elgg_echo('messages:success:read');
	foreach ($message_ids as $guid) {
		$message = get_entity($guid);
		if ($message && $message->getSubtype() == 'messages' && $message->canEdit()) {
			$message->readYet = 1;
		}
	}
}

system_message($success_msg);
forward(REFERER);
