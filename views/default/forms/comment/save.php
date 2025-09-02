<?php
/**
 * Form for adding and editing comments
 *
 * @uses ElggEntity  $vars['entity']  The entity being commented
 * @uses ElggComment $vars['comment'] The comment being edited
 * @uses bool        $vars['inline']  Show a single line version of the form?
 */

if (!elgg_is_logged_in()) {
	return;
}

elgg_import_esm('elgg/comments');

/* @var \ElggEntity $entity */
$entity = elgg_extract('entity', $vars);
if ($entity instanceof \ElggEntity) {
	echo elgg_view('input/hidden', [
		'name' => 'entity_guid',
		'value' => $entity->guid,
	]);
}

/* @var \ElggComment $comment */
$comment = elgg_extract('comment', $vars);

$is_edit = $comment instanceof \ElggComment && $comment->canEdit();
if ($is_edit) {
	echo elgg_view('input/hidden', [
		'name' => 'comment_guid',
		'value' => $comment->guid,
	]);
}

if (elgg_extract('inline', $vars, false)) {
	echo elgg_view_field([
		'#type' => 'fieldset',
		'align' => 'horizontal',
		'fields' => [
			[
				'#type' => 'text',
				'#class' => 'elgg-field-stretch',
				'name' => 'generic_comment',
				'value' => $is_edit ? $comment->description : '',
				'required' => true,
			],
			[
				'#type' => 'submit',
				'text' => $is_edit ? elgg_echo('save') : elgg_echo('comment'),
			],
		],
	]);
	return;
}

echo elgg_view_field([
	'#type' => 'longtext',
	'#label' => $is_edit ? elgg_echo('generic_comments:edit') : elgg_echo('generic_comments:add'),
	'name' => 'generic_comment',
	'value' => $is_edit ? $comment->description : '',
	'required' => true,
	'editor_type' => 'simple',
]);

$footer_fields = [[
	'#type' => 'submit',
	'text' => $is_edit ? elgg_echo('save') : elgg_echo('comment'),
]];

if ($is_edit) {
	$footer_fields[] = [
		'#type' => 'button',
		'text' => elgg_echo('cancel'),
		'class' => 'elgg-button-cancel',
		'href' => $entity ? $entity->getURL() : '#',
	];
}

elgg_set_form_footer(elgg_view_field([
	'#type' => 'fieldset',
	'align' => 'horizontal',
	'fields' => $footer_fields,
]));
