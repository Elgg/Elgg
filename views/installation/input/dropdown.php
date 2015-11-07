<?php
/**
 * Elgg dropdown input
 * Displays a dropdown input field
 *
 * @uses $vars['value']           The current value, if any
 * @uses $vars['name']            The name of the input field
 * @uses $vars['options']         An associative array of "value" => "option" where "value" is an internal name and "option" is
 *                                the value displayed on the button. Replaces $vars['options'] when defined.
 * @uses $vars['options_values']         Alias for $vars['options'] since 2.0-beta3.
 */

$class = "elgg-input-dropdown";

// options_values is aliased to options, the options_values format is moved to the options key.
if (isset($vars['options_values'])) {
	$vars['options'] = $vars['options_values'];
	unset($vars['options_values']);
}
?>
<select name="<?php echo $vars['name']; ?>" class="<?php echo $class; ?>">
<?php
foreach ($vars['options'] as $value => $option) {
	if ($value != $vars['value']) {
		echo "<option value=\"$value\">{$option}</option>";
	} else {
		echo "<option value=\"$value\" selected=\"selected\">{$option}</option>";
	}
}
?>
</select>
