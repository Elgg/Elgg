<?php
/**
 * Elgg URL input
 * Displays a URL input field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['class'] Additional CSS class
 */

if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-url {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-url";
}

$defaults = array(
	'value' => '',
	'disabled' => false,
	'autocapitalize' => 'off',
	'autocorrect' => 'off',
);

$vars = array_merge($defaults, $vars);

?>

<input type="url" <?php echo elgg_format_attributes($vars); ?> />
