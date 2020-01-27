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

elgg_require_js('elgg/comments');

$entity = elgg_extract('entity', $vars);
/* @var ElggEntity $entity */

$comment = elgg_extract('comment', $vars);
/* @var ElggComment $comment */

$inline = (bool) elgg_extract('inline', $vars, false);

if ($entity) {
	echo elgg_view('input/hidden', [
		'name' => 'entity_guid',
		'value' => $entity->guid,
	]);
}

$comment_text = '';
$footer = '';
if ($comment && $comment->canEdit()) {
	echo elgg_view('input/hidden', [
		'name' => 'comment_guid',
		'value' => $comment->guid,
	]);
	$comment_label  = elgg_echo("generic_comments:edit");
	$footer .= elgg_view('input/submit', ['value' => elgg_echo('save')]);
	$comment_text = $comment->description;
} else {
	$comment_label  = elgg_echo("generic_comments:add");
	$footer .= elgg_view('input/submit', ['value' => elgg_echo('comment')]);
}

if ($inline) {
	$form = elgg_view('input/text', [
		'name' => 'generic_comment',
		'value' => $comment_text,
		'required' => true,
	]);
	
	$form .= $footer;
	echo elgg_format_element('div', [
		'class' => 'elgg-level',
	], $form);
} else {
	echo elgg_view_field([
		'#type' => 'longtext',
		'#label' => $comment_label,
		'name' => 'generic_comment',
		'value' => $comment_text,
		'required' => true,
		'editor_type' => 'simple',
	]);
	
	if ($comment) {
		$footer .= elgg_view('input/button', [
			'value' => elgg_echo('cancel'),
			'class' => 'elgg-button-cancel mlm',
			'href' => $entity ? $entity->getURL() : '#',
		]);
	}
	
	elgg_set_form_footer($footer);
}
