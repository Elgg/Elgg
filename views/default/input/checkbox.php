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
 * @uses $vars['name']        Name of the checkbox
 * @uses $vars['value']       Value of the checkbox
 * @uses $vars['default']     The default value to submit if not checked.
 *                            Optional, defaults to 0. Set to false for no default.
 * @uses $vars['checked']     Whether this checkbox is checked
 * @uses $vars['label']       Optional label string
 * @uses $vars['class']       Additional CSS class
 * @uses $vars['label_class'] Optional class for the label
 */

if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-checkbox {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-checkbox";
}

$defaults = array(
	'default' => 0,
	'disabled' => false,
);

$vars = array_merge($defaults, $vars);

$default = $vars['default'];
unset($vars['default']);

if (isset($vars['name']) && $default !== false) {
	echo "<input type=\"hidden\" name=\"{$vars['name']}\" value=\"$default\"/>";
}

if (isset($vars['label'])) {
	if (isset($vars['label_class'])) {
		echo "<label class=\"{$vars['label_class']}\">";
	} else {
		echo "<label>";
	}
}
?>
<input type="checkbox" <?php echo elgg_format_attributes($vars); ?> />
<?php
if (isset($vars['label'])) {
	echo "{$vars['label']}</label>";
}
