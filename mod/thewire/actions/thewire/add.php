<?php
/**
 * Action for adding a wire post
 */

$body = get_input('body');
$parent_guid = (int) get_input('parent_guid');

// make sure the post isn't blank
if (empty($body)) {
	return elgg_error_response(elgg_echo('thewire:blank'));
}

$guid = thewire_save_post($body, elgg_get_logged_in_user_guid(), ACCESS_PUBLIC, $parent_guid, 'site');
if ($guid === false) {
	return elgg_error_response(elgg_echo('thewire:notsaved'));
}

$forward = null;

// if reply, forward to thread display page
if ($parent_guid) {
	$parent = get_entity($parent_guid);
	if ($parent instanceof \ElggWire) {
		$forward = elgg_generate_url('collection:object:thewire:thread', [
			'guid' => $parent->wire_thread,
		]);
	}
}

return elgg_ok_response('', elgg_echo('thewire:posted'), $forward);
