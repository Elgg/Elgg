<?php
/**
 * Elgg ajax edit comment form
 *
 * @package Elgg
 * @subpackage Core
 */

$guid = get_input('guid');

$comment = get_entity($guid);
if (!$comment instanceof ElggComment || !$comment->canEdit()) {
	return;
}

$entity = $comment->getContainerEntity();
elgg_set_page_owner_guid($entity->getContainerGUID());

$form_vars = [
	'class' => 'hidden mvl',
	'id' => "edit-comment-{$guid}",
];
$body_vars = ['comment' => $comment];
echo elgg_view_form('comment/save', $form_vars, $body_vars);
