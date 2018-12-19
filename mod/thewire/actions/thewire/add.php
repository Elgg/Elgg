<?php
/**
 * Action for adding a wire post
 */

// don't filter since we strip and filter escapes some characters
$body = get_input('body', '', false);
$parent_guid = (int) get_input('parent_guid');

// make sure the post isn't blank
if (empty($body)) {
	return elgg_error_response(elgg_echo('thewire:blank'));
}

$guid = thewire_save_post($body, elgg_get_logged_in_user_guid(), ACCESS_PUBLIC, $parent_guid, 'site');
if (!$guid) {
	return elgg_error_response(elgg_echo('thewire:notsaved'));
}

$forward = null;

// if reply, forward to thread display page
if ($parent_guid) {
	$parent = get_entity($parent_guid);
	if ($parent instanceof ElggWire) {
		$forward = elgg_generate_url('collection:object:thewire:thread', [
			'guid' => $parent->wire_thread,
		]);
	}
}

return elgg_ok_response('', elgg_echo('thewire:posted'), $forward);
