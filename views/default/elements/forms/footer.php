<?php
/**
 * Wrap form footer
 *
 * @uses $vars['footer']      Form footer
 * @uses $vars['action_name'] Action name
 * @uses $vars['body_vars']   Vars used in the contents of the form
 * @uses $vars['form_vars']   Vars used to format the form
 */

$footer = (string) elgg_extract('footer', $vars);

// add automatic sticky form support
$form_vars = (array) elgg_extract('form_vars', $vars);
if ((bool) elgg_extract('sticky_enabled', $form_vars, false)) {
	$footer .= elgg_view_field([
		'#type' => 'hidden',
		'name' => '_elgg_sticky_form_name',
		'value' => (string) elgg_extract('sticky_form_name', $form_vars),
	]);
	
	$ignored_fields = (array) elgg_extract('sticky_ignored_fields', $form_vars);
	if (!empty($ignored_fields)) {
		$footer .= elgg_view_field([
			'#type' => 'hidden',
			'name' => '_elgg_sticky_ignored_fields',
			'value' => implode(',', $ignored_fields),
		]);
	}
}

if (empty($footer)) {
	return;
}

echo elgg_format_element('div', [
	'class' => 'elgg-foot elgg-form-footer',
], $footer);
