<?php
/**
 * Action for adding a wire post
 * 
 */

// don't filter since we strip and filter escapes some characters
$body = get_input('body', '', false);

$access_id = ACCESS_PUBLIC;
$method = 'site';
$parent_guid = (int) get_input('parent_guid');

// make sure the post isn't blank
if (empty($body)) {
	register_error(elgg_echo("thewire:blank"));
	forward(REFERER);
}

$guid = thewire_save_post($body, get_loggedin_userid(), $access_id, $parent_guid, $method);
if (!$guid) {
	register_error(elgg_echo("thewire:error"));
	forward(REFERER);
}

// Send response to original poster if not already registered to receive notification
if ($parent_guid) {
	thewire_send_response_notification($guid, $parent_guid, $user);
	$parent = get_entity($parent_guid);
	forward("thewire/thread/$parent->wire_thread");
}

system_message(elgg_echo("thewire:posted"));
forward(REFERER);
