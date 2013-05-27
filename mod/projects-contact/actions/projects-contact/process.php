<?php
/**
* Process a set of messages
*/

$message_ids = get_input('message_id', array());

if (!$message_ids) {
	register_error(elgg_echo('projects_contact:error:messages_not_selected'));
	forward(REFERER);
}

register_error(elgg_echo(var_dump($message_ids)));

$delete_flag = get_input('delete', false);
$read_flag = get_input('read', false);

if ($delete_flag) {
	$success_msg = elgg_echo('projects_contact:success:delete');
	foreach ($message_ids as $guid) {
		$message = get_entity($guid);
		if ($message && $message->getSubtype() == 'projects-contact' && $message->canEdit()) {
			$message->delete();
		}
	}
} else {
	$success_msg = elgg_echo('projects_contact:success:read');
	foreach ($message_ids as $guid) {
		$message = get_entity($guid);
		if ($message && $message->getSubtype() == 'projects-contact' && $message->canEdit()) {
			$message->readed = true;
			$message->save();
			
		}
	}
}

system_message($success_msg);
forward(REFERER);
