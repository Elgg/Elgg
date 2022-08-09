<?php
/**
 * Elgg ajax edit comment form
 */

$guid = (int) get_input('guid');

$comment = get_entity($guid);
if (!$comment instanceof ElggComment || !$comment->canEdit()) {
	return false;
}

$entity = $comment->getContainerEntity();
elgg_set_page_owner_guid($entity->getContainerGUID());

$form_vars = [
	'class' => 'hidden mvl',
	'id' => "edit-comment-{$guid}",
];
$body_vars = [
	'comment' => $comment,
];
echo elgg_view_form('comment/save', $form_vars, $body_vars);
