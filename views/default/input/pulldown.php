<?php
/**
 * Elgg pulldown input
 * Displays a pulldown input field
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['js'] Any Javascript to enter into the input tag
 * @uses $vars['internalname'] The name of the input field
 * @uses $vars['options'] An array of strings representing the options for the pulldown field
 * @uses $vars['options_values'] An associative array of "value" => "option" where "value" is an internal name and "option" is
 * 								 the value displayed on the button. Replaces $vars['options'] when defined.
 */

$class = $vars['class'];
if (!$class) {
	$class = "input_pulldown";
}

if (!isset($vars['value']) || $vars['value'] === FALSE) {
	$vars['value'] = elgg_get_sticky_value($vars['internalname']);
}

?>
<select name="<?php echo $vars['internalname']; ?>" <?php if (isset($vars['internalid'])) echo "id=\"{$vars['internalid']}\""; ?> <?php echo $vars['js']; ?> <?php if ($vars['disabled']) echo ' disabled="yes" '; ?> class="<?php echo $class; ?>">
<?php

if ($vars['options_values']) {
	foreach($vars['options_values'] as $value => $option) {
		$value = htmlentities($value, ENT_QUOTES, 'UTF-8');
		$option = htmlentities($option, ENT_QUOTES, 'UTF-8');

		// use strcmp() because (0 == 'string') is true
		if (strcmp($value, $vars['value']) == 0) {
			echo "<option value=\"$value\" selected=\"selected\">$option</option>";
		} else {
			echo "<option value=\"$value\">$option</option>";
		}
	}
} else {
	foreach($vars['options'] as $option) {
		$option = htmlentities($option, ENT_QUOTES, 'UTF-8');

		if (strcmp($value, $vars['value']) == 0) {
			echo "<option selected=\"selected\">$option</option>";
		} else {
			echo "<option>$option</option>";
		}
	}
}
?>
</select>