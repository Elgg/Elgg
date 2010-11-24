<?php
/**
 * Elgg checkbox input
 * Displays a checkbox input tag
 * 
 * @package Elgg
 * @subpackage Core
 *
 *
 * Pass input tag attributes as key value pairs. For a list of allowable
 * attributes, see http://www.w3schools.com/tags/tag_input.asp
 * 
 * @uses mixed $vars['default'] The default value to submit if not checked.
 *                              Optional, defaults to 0. Set to false for no default.
 */

$defaults = array(
	'class' => 'input_checkbox',
);

$vars = array_merge($defaults, $vars);

if (isset($vars['default'])) {
	$default = $vars['default'];
	unset($vars['default']);
} else {
	$default = 0;
}

if (isset($vars['name']) && $default !== false) {
	echo "<input type=\"hidden\" name=\"{$vars['name']}\" value=\"$default\"/>";
}

?>

<input type="checkbox" <?php echo elgg_format_attributes($vars); ?> />