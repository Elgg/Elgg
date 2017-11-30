<?php
/**
 * Wrap form footer
 *
 * @uses $vars['footer']      Form footer
 * @uses $vars['action_name'] Action name
 * @uses $vars['body_vars']   Vars used in the contents of the form
 * @uses $vars['form_vars']   Vars used to format the form
 */

$footer = elgg_extract('footer', $vars);
if (empty($footer)) {
	return;
}

echo elgg_format_element('div', [
	'class' => 'elgg-foot elgg-form-footer',
], $footer);
