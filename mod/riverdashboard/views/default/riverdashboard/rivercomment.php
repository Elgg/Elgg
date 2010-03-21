<?php
/**
 * Elgg comments add on river form
 * @uses $vars['entity']
 */
	
if (isset($vars['entity']) && isloggedin()) {
	 $form_body = elgg_view('input/text',array('internalname' => 'generic_comment', 'value' => 'Add a comment...'));
	 $form_body .= elgg_view('input/hidden', array('internalname' => 'entity_guid', 'value' => $vars['entity']->getGUID()));
	 $form_body .= elgg_view('input/submit', array('value' => elgg_echo("post")));
	 echo elgg_view('input/form', array('body' => $form_body, 'action' => "{$vars['url']}action/comments/add"));
}