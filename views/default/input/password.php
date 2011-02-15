<?php
/**
 * Elgg password input
 * Displays a password input field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['js'] Any Javascript to enter into the input tag
 * @uses $vars['name'] The name of the input field
 *
 */

$defaults = array(
	'class' => 'elgg-input-password',
	'disabled' => FALSE,
);

$attrs = array_merge($defaults, $vars);
?>

<input type="password" <?php echo elgg_format_attributes($attrs); ?> />