<?php
/**
 * Elgg date input
 * Displays a text field with a popup date picker.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The current value, if any (as a unix timestamp)
 *
 */

$defaults = array(
	'value' => '',
	'class' => '',
);

$vars = array_merge($defaults, $vars);

//@todo popup_calendar deprecated in 1.8.  Remove in 2.0
$vars['class'] = trim("elgg-input-date popup_calendar {$vars['class']}");

if ($vars['value'] > 86400) {
	$vars['value'] = date('n/d/Y', $vars['value']);
}

$attributes = elgg_format_attributes($vars);

?>
<input type="text" <?php echo $attributes; ?> />