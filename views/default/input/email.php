<?php
/**
 * Elgg email input
 * Displays an email input field
 *
 * @package Elgg
 * @subpackage Core
 */

$defaults = array(
	'class' => 'elgg-input-email',
	'disabled' => FALSE,
);

$vars = array_merge($defaults, $vars);

?>

<input type="text" <?php echo elgg_format_attributes($vars); ?> />