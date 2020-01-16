<?php
/**
 * Elgg password input
 * Displays a password input field
 *
 * @uses $vars['value']                     The current value, if any
 * @uses $vars['name']                      The name of the input field
 * @uses $vars['class']                     Additional CSS class
 * @uses $vars['always_empty']              If for some reason you want to set a value to a password field, set this field to false. Best practice is to not populate password fields.
 * @uses $vars['add_security_requirements'] Should the security password requirements validation rules be added (default: false)
 */

$vars['class'] = elgg_extract_class($vars, 'elgg-input-password');

$defaults = [
	'disabled' => false,
	'autocapitalize' => 'off',
	'autocorrect' => 'off',
	'type' => 'password',
];

$vars = array_merge($defaults, $vars);

$always_empty = elgg_extract('always_empty', $vars, true);
unset($vars['always_empty']);

if ($always_empty) {
	unset($vars['value']);
}

echo elgg_format_element('input', $vars);
