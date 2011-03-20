<?php
/**
 * Post a reply to discussion topic
 *
 */

gatekeeper();

// Get input
$entity_guid = (int) get_input('entity_guid');
$text = get_input('group_topic_post');

// reply cannot be empty
if (empty($text)) {
	register_error(elgg_echo('grouppost:nopost'));
	forward(REFERER);
}

$topic = get_entity($entity_guid);
if (!$topic) {
	register_error(elgg_echo('grouppost:nopost'));
	forward(REFERER);
}

$user = get_loggedin_user();

$group = $topic->getContainerEntity();
if (!$group->canWriteToContainer($user)) {
	register_error(elgg_echo('groups:notmember'));
	forward(REFERER);
}


// add the reply to the forum topic
$reply_id = $topic->annotate('group_topic_post', $text, $topic->access_id, $user->guid);
if ($reply_id == false) {
	system_message(elgg_echo('groupspost:failure'));
	forward(REFERER);
}

add_to_river('river/annotation/group_topic_post/reply', 'reply', $user->guid, $topic->guid, "", 0, $reply_id);

system_message(elgg_echo('groupspost:success'));

forward(REFERER);
