<?php
/**
 * Process a set of messages
 */

$message_guids = get_input('message_id', []);
if (!$message_guids) {
	return elgg_error_response(elgg_echo('messages:error:messages_not_selected'));
}

$delete_flag = (bool) get_input('delete', false);
if ($delete_flag) {
	foreach ($message_guids as $guid) {
		$message = get_entity($guid);
		if ($message instanceof ElggMessage && $message->canEdit()) {
			$message->delete();
		}
	}
	return elgg_ok_response('', elgg_echo('messages:success:delete'));
}

// mark as read
foreach ($message_guids as $guid) {
	$message = get_entity($guid);
	if ($message instanceof ElggMessage && $message->canEdit()) {
		$message->readYet = 1;
	}
}
return elgg_ok_response('', elgg_echo('messages:success:read'));
