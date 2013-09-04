<?php
/**
 * Form for adding and editing comments
 *
 * @package Elgg
 *
 * @uses ElggEntity  $vars['entity']  The entity being commented
 * @uses ElggComment $vars['comment'] The comment being edited
 * @uses bool        $vars['inline']  Show a single line version of the form?
 */

if (elgg_is_logged_in()) {
	$entity_guid_input = '';
	if (isset($vars['entity'])) {
		$entity_guid_input = elgg_view('input/hidden', array(
			'name' => 'entity_guid',
			'value' => $vars['entity']->getGUID()
		));
	}

	$comment_text = '';
	$comment_guid_input = '';
	if (isset($vars['comment']) && $vars['comment']->canEdit()) {
		$entity_guid_input = elgg_view('input/hidden', array(
			'name' => 'comment_guid',
			'value' => $vars['comment']->getGUID()
		));
		$comment_label  = elgg_echo("generic_comments:edit");
		$submit_input = elgg_view('input/submit', array('value' => elgg_echo('save')));
		$comment_text = $vars['comment']->description;
	} else {
		$comment_label  = elgg_echo("generic_comments:add");
		$submit_input = elgg_view('input/submit', array('value' => elgg_echo('comment')));
	}

	$inline = elgg_extract('inline', $vars, false);

	if ($inline) {
		$comment_input = elgg_view('input/text', array(
			'name' => 'generic_comment',
			'value' => $comment_text,
		));

		echo $comment_input . $entity_guid_input . $comment_guid_input . $submit_input;
	} else {
		$comment_input = elgg_view('input/longtext', array(
			'name' => 'generic_comment',
			'value' => $comment_text,
		));

		echo <<<FORM
	<div>
		<label>$comment_label</label>
		$comment_input
	</div>
	<div class="elgg-foot">
		$comment_guid_input
		$entity_guid_input
		$submit_input
	<div>
FORM;
	}
}
