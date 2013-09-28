<?php
/**
 * Elgg password input
 * Displays a password input field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['name']  The name of the input field
 * @uses $vars['class'] Additional CSS class
 */

if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-password {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-password";
}

$defaults = array(
	'disabled' => false,
	'value' => '',
	'autocapitalize' => 'off',
	'autocorrect' => 'off',
);

$attrs = array_merge($defaults, $vars);
?>

<input type="password" <?php echo elgg_format_attributes($attrs); ?> />
