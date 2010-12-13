<?php
/**
 * Inline comment form body
 *
 * @uses $vars['entity']
 */

if (isset($vars['entity']) && isloggedin()) {
	echo elgg_view('input/text', array('internalname' => 'generic_comment'));
	echo elgg_view('input/hidden', array(
		'internalname' => 'entity_guid',
		'value' => $vars['entity']->getGUID()
	));
	echo elgg_view('input/submit', array('value' => elgg_echo('comment')));
}
