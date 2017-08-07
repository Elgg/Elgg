<?php
/**
 * Delete comment entity
 */

$comment_guid = get_input('guid');
$comment = get_entity($comment_guid);

if (!elgg_instanceof($comment, 'object', 'comment') || !$comment->canEdit()) {
	return elgg_error_response(elgg_echo('generic_comment:notfound'));
}

if (!$comment->delete()) {
	return elgg_error_response(elgg_echo('generic_comment:notdeleted'));
}

return elgg_ok_response('', elgg_echo('generic_comment:deleted'));
