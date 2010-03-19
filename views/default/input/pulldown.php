<?php
/**
 * Elgg pulldown input
 * Displays a pulldown input field
 *
 * NB: Default values of FALSE or NULL will match '' (empty string) and not 0.
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

?>
<select name="<?php echo $vars['internalname']; ?>" <?php if (isset($vars['internalid'])) echo "id=\"{$vars['internalid']}\""; ?> <?php echo $vars['js']; ?> <?php if ($vars['disabled']) echo ' disabled="yes" '; ?> class="<?php echo $class; ?>">
<?php

if ($vars['options_values']) {
	foreach($vars['options_values'] as $value => $option) {

		$encoded_value = htmlentities($value, ENT_QUOTES, 'UTF-8');
		$encoded_option = htmlentities($option, ENT_QUOTES, 'UTF-8');

		if ((string)$value == (string)$vars['value']) {
			echo "<option value=\"$encoded_value\" selected=\"selected\">$encoded_option</option>";
		} else {
			echo "<option value=\"$encoded_value\">$encoded_option</option>";
		}
	}
} else {
	foreach($vars['options'] as $option) {
		$encoded_option = htmlentities($option, ENT_QUOTES, 'UTF-8');

		if ((string)$value == (string)$vars['value']) {
			echo "<option selected=\"selected\">$encoded_option</option>";
		} else {
			echo "<option>$encoded_option</option>";
		}
	}
}
?>
</select>