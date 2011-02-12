<?php
/**
 * Elgg file input
 * Displays a file input field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The current value if any
 *
 */

if (!empty($vars['value'])) {
	echo elgg_echo('fileexists') . "<br />";
}

$defaults = array(
	'class' => 'elgg-input-file',
	'disabled' => FALSE,
	'size' => 30,	
);

$attrs = array_merge($defaults, $vars);

?>
<input type="file"  <?php echo elgg_format_attributes($attrs)?> />