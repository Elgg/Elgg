<?php
/**
 * Elgg email input
 * Displays an email input field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['class'] Additional CSS class
 */

if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-email {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-email";
}

$defaults = array(
	'disabled' => false,
	'autocapitalize' => 'off',
	'autocorrect' => 'off',
);

$vars = array_merge($defaults, $vars);

?>

<input type="email" <?php echo elgg_format_attributes($vars); ?> />
