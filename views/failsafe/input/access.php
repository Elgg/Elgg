<?php
/**
 * Elgg access level input
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
 *
 */

if (isset($vars['class'])) {
	$class = $vars['class'];
}
if (!$class) {
	$class = "input-access";
}

if ((!isset($vars['options'])) || (!is_array($vars['options']))) {
	$vars['options'] = array();
	$vars['options'] = get_write_access_array();
}

if (is_array($vars['options']) && sizeof($vars['options']) > 0) {

	?>

	<select name="<?php echo $vars['internalname']; ?>" <?php if (isset($vars['js'])) echo $vars['js']; ?> <?php if ((isset($vars['disabled'])) && ($vars['disabled'])) echo ' disabled="yes" '; ?> class="<?php echo $class; ?>">
	<?php

		foreach($vars['options'] as $key => $option) {
			if ($key != $vars['value']) {
				echo "<option value=\"{$key}\">{$option}</option>";
			} else {
				echo "<option value=\"{$key}\" selected=\"selected\">{$option}</option>";
			}
		}

	?>
	</select>

	<?php

}