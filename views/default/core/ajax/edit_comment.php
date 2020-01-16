<?php
/**
 * Elgg ajax edit comment form
 */

$guid = get_input('guid');

$comment = get_entity($guid);
$entity = $comment->getContainerEntity();
elgg_set_page_owner_guid($entity->getContainerGUID());

if (!($comment instanceof ElggComment) || !$comment->canEdit()) {
	return false;
}

$form_vars = [
	'class' => 'hidden mvl',
	'id' => "edit-comment-{$guid}",
];
$body_vars = [
	'comment' => $comment,
];
echo elgg_view_form('comment/save', $form_vars, $body_vars);
