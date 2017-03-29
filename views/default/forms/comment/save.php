<?php

/**
 * Form for adding and editing comments
 *
 * @package Elgg
 *
 * @uses ElggEntity  $vars['entity']  The entity being commented
 * @uses ElggComment $vars['comment'] The comment being edited
 */
if (!elgg_is_logged_in()) {
	return;
}

$entity = elgg_extract('entity', $vars);
/* @var ElggEntity $entity */

$comment = elgg_extract('comment', $vars);
/* @var ElggComment $comment */

$footer = [];

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'entity_guid',
	'value' => $entity->guid,
]);

$comment_text = '';
if ($comment && $comment->canEdit()) {
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'comment_guid',
		'value' => $comment->guid,
	]);

	$comment_label = elgg_echo('generic_comments:edit');
	$footer[] = [
		'#type' => 'submit',
		'value' => elgg_echo('save'),
	];
	$comment_text = $comment->description;

	$footer[] = [
		'#type' => 'reset',
		'value' => elgg_echo('cancel'),
		'class' => 'elgg-button-cancel',
	];
} else {
	$comment_label = elgg_echo('generic_comments:add');
	$footer[] = [
		'#type' => 'submit',
		'value' => elgg_echo('comment'),
	];
}

echo elgg_view_field([
	'#type' => 'longtext',
	'#label' => $comment_label,
	'visual' => false,
	'name' => 'generic_comment',
	'value' => $comment_text,
	'required' => true,
	'editor_type' => 'simple',
	'rows' => 2,
]);

elgg_set_form_footer(elgg_view_field([
	'#type' => 'fieldset',
	'align' => 'horizontal',
	'fields' => $footer,
]));
