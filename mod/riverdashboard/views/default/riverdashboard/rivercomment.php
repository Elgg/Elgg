<?php
/**
 * Elgg comments add on river form
 * @uses $vars['entity']
 */

if (isset($vars['entity']) && isloggedin()) {
	$form_body = "<a class=\"river_comment_form_button\">Add comment</a>";
	$form_body .= "<div class=\"river_comment_form\" style=\"display:none;\">";
	$form_body .= elgg_view('input/text',array('internalname' => 'generic_comment', 'value' => 'Add a comment...'));
	$form_body .= elgg_view('input/hidden', array('internalname' => 'entity_guid', 'value' => $vars['entity']->getGUID()));
	$form_body .= elgg_view('input/submit', array('value' => elgg_echo("post")));
	$form_body .= "</div>";
	echo elgg_view('input/form', array('body' => $form_body, 'action' => "{$vars['url']}action/comments/add"));
}