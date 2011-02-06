<?php
/**
 * Elgg text input
 * Displays a text input field
 *
 * @package Elgg
 * @subpackage Core
 */

$defaults = array(
	'class' => 'elgg-input-text',
	'disabled' => FALSE,
);

$vars = array_merge($defaults, $vars);

?>

<input type="text" <?php echo elgg_format_attributes($vars); ?> />