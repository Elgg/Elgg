<?php
/**
 * Quickly open/close a discussion
 */

$guid = (int) get_input('guid');
if (empty($guid)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$entity = get_entity($guid);
if (!$entity instanceof ElggDiscussion || !$entity->canEdit()) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

if ($entity->status === 'closed') {
	$entity->status = 'open';
	
	return elgg_ok_response('', elgg_echo('discussion:topic:toggle_status:open'));
}

$entity->status = 'closed';
	
return elgg_ok_response('', elgg_echo('discussion:topic:toggle_status:closed'));
