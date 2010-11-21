<?php
/**
 * Elgg checkbox input
 * Displays a checkbox input field
 * 
 * @package Elgg
 * @subpackage Core
 */

$defaults = array(
	'class' => 'input_checkbox',
);

$vars = array_merge($defaults, $vars);

?>

<input type="checkbox" <?php echo elgg_format_attributes($vars); ?> />