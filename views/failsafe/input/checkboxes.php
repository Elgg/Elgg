<?php
/**
 * Elgg checkbox input
 * Displays a checkbox input field
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['js'] Any Javascript to enter into the input tag
 * @uses $vars['internalname'] The name of the input field
 * @uses $vars['options'] An array of strings representing the options for the checkbox field
 *
 */

$class = $vars['class'];
if (!$class) {
	$class = "input-checkboxes";
}

foreach($vars['options'] as $label => $option) {
	//if (!in_array($option,$vars['value'])) {
	if (is_array($vars['value'])) {
		if (!in_array($option,$vars['value'])) {
			$selected = "";
		} else {
			$selected = "checked = \"checked\"";
		}
	} else {
		if ($option != $vars['value']) {
			$selected = "";
		} else {
			$selected = "checked = \"checked\"";
		}
	}
	$labelint = (int) $label;
	if ("{$label}" == "{$labelint}") {
		$label = $option;
	}

	$disabled = "";
	if ($vars['disabled']) {
		$disabled = ' disabled="yes" ';
	}
	echo "<label><input type=\"checkbox\" $disabled {$vars['js']} name=\"{$vars['internalname']}[]\" {$selected} value=\"".htmlentities($option, ENT_QUOTES, 'UTF-8')."\" {$selected} class=\"$class\" />{$label}</label><br />";
}