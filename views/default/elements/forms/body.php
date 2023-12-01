<?php
/**
 * Wrap form body
 *
 * @uses $vars['body']        Form body
 * @uses $vars['action_name'] Action name
 * @uses $vars['body_vars']   Vars used in the contents of the form
 * @uses $vars['form_vars']   Vars used to format the form
 */

$body = (string) elgg_extract('body', $vars);
if (empty($body)) {
	return;
}

echo elgg_format_element('div', ['class' => 'elgg-form-body'], $body);
