<?php
/**
 * Elgg text input
 * Displays a text input field
 *
 * @package Elgg
 * @subpackage Core
 */

$defaults = array(
	'class' => 'input_text',
	'disabled' => FALSE,
);

$vars = array_merge($defaults, $vars);

if (!isset($vars['value']) || $vars['value'] === FALSE) {
	$vars['value'] = elgg_get_sticky_value($vars['internalname']);
}
?>

<input type="text" <?php echo elgg_format_attributes($vars); ?> />