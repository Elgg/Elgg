<?php
/**
 * Delete topic action
 *
 */

$topic_guid = (int) get_input('guid');

$topic = get_entity($topic_guid);
if (!$topic || !$topic->getSubtype() == "groupforumtopic") {
	register_error(elgg_echo('discussion:error:notdeleted'));
	forward(REFERER);
}

if (!$topic->canEdit()) {
	register_error(elgg_echo('discussion:error:permissions'));
	forward(REFERER);
}

$container = $topic->getContainerEntity();

$result = $topic->delete();
if ($result) {
	system_message(elgg_echo('discussion:topic:deleted'));
} else {
	register_error(elgg_echo('discussion:error:notdeleted'));
}

forward("discussion/owner/$container->guid");
