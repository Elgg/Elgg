<?php
/**
 * Elgg ajax edit form for a discussion reply
 *
 * @package ElggGroups
 */

$guid = get_input('guid');

$reply = get_entity($guid);
if (!elgg_instanceof($reply, 'object', 'discussion_reply') || !$reply->canEdit()) {
	return false;
}

$form_vars = array(
	'class' => 'hidden mvl',
	'id' => "edit-discussion-reply-{$guid}",
);
$body_vars = array('entity' => $reply);
echo elgg_view_form('discussion/reply/save', $form_vars, $body_vars);