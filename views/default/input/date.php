<?php
/**
 * Elgg date input
 * Displays a text field with a popup date picker.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The current value, if any (as a unix timestamp)
 * @uses $vars['class'] Additional CSS class
 */

//@todo popup_calendar deprecated in 1.8.  Remove in 2.0
if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-date popup_calendar {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-date popup_calendar";
}

$defaults = array(
	'value' => '',
	'disabled' => false,
);

$vars = array_merge($defaults, $vars);


if ($vars['value'] > 86400) {
	$vars['value'] = date('n/d/Y', $vars['value']);
}

$attributes = elgg_format_attributes($vars);

?>
<input type="text" <?php echo $attributes; ?> />