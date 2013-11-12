<?php
/**
 * Elgg ajax edit form for a discussion reply
 *
 * @package ElggGroups
 */

$guid = elgg_extract('guid', $vars);
$hidden = elgg_extract('hidden', $vars, true);

$reply = get_entity($guid);
if (!elgg_instanceof($reply, 'object', 'discussion_reply', 'ElggDiscussionReply') || !$reply->canEdit()) {
	return false;
}

$form_vars = array(
	'class' => ($hidden ? 'hidden ' : '') . 'mvl',
	'id' => "edit-discussion-reply-{$guid}",
);
$body_vars = array('entity' => $reply);
echo elgg_view_form('discussion/reply/save', $form_vars, $body_vars);