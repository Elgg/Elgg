<?php
/**
 * Delete topic action
 */

$topic_guid = (int) get_input('guid');
$topic = get_entity($topic_guid);

if (!$topic instanceof ElggDiscussion) {
	return elgg_error_response(elgg_echo('discussion:error:notdeleted'));
}

if (!$topic->canDelete()) {
	return elgg_error_response(elgg_echo('discussion:error:permissions'));
}

$container = $topic->getContainerEntity();

if (!$topic->delete()) {
	return elgg_error_response(elgg_echo('discussion:error:notdeleted'));
}

return elgg_ok_response('', elgg_echo('discussion:topic:deleted'), "discussion/owner/{$container->guid}");
