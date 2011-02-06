<?php
/**
 * Elgg URL input
 * Displays a URL input field
 *
 * @package Elgg
 * @subpackage Core
 */

$defaults = array(
	'class' => 'elgg-input-url',
);

$vars = array_merge($defaults, $vars);

?>

<input type="text" <?php echo elgg_format_attributes($vars); ?> />
