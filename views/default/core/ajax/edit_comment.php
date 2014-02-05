<?php
/**
 * Elgg ajax edit comment form
 *
 * @package Elgg
 * @subpackage Core
 */

$guid = get_input('guid');

$comment = get_entity($guid);
$entity = $comment->getContainerEntity();
elgg_set_page_owner_guid($entity->getContainerGUID());

if (!elgg_instanceof($comment, 'object', 'comment') || !$comment->canEdit()) {
	return false;
}

$form_vars = array(
	'class' => 'hidden mvl',
	'id' => "edit-comment-{$guid}",
);
$body_vars = array('comment' => $comment);
echo elgg_view_form('comment/save', $form_vars, $body_vars);
