<?php
/**
 * Elgg dropdown input
 * Displays a dropdown input field
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['name'] The name of the input field
 * @uses $vars['options'] An array of strings representing the options for the dropdown field
 * @uses $vars['options_values'] An associative array of "value" => "option" where "value" is an internal name and "option" is
 * 								 the value displayed on the button. Replaces $vars['options'] when defined.
 */


$class = $vars['class'];
if (!$class) {
	$class = "elgg-input-dropdown";
}
?>
<select name="<?php echo $vars['name']; ?>" <?php if ($vars['disabled']) echo ' disabled="yes" '; ?> class="<?php echo $class; ?>">
<?php
if ($vars['options_values']) {
	foreach($vars['options_values'] as $value => $option) {
		if ($value != $vars['value']) {
			echo "<option value=\"$value\">{$option}</option>";
		} else {
			echo "<option value=\"$value\" selected=\"selected\">{$option}</option>";
		}
	}
} else {
	foreach($vars['options'] as $option) {
		if ($option != $vars['value']) {
			echo "<option>{$option}</option>";
		} else {
			echo "<option selected=\"selected\">{$option}</option>";
		}
	}
}
?>
</select>