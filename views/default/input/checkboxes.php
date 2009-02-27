<?php

	/**
	 * Elgg checkbox input
	 * Displays a checkbox input field
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['value'] The current value, if any
	 * @uses $vars['js'] Any Javascript to enter into the input tag
	 * @uses $vars['internalname'] The name of the input field
	 * @uses $vars['options'] An array of strings representing the label => options for the checkbox field
	 * 
	 */

	$class = $vars['class'];
	if (!$class) $class = "input-checkboxes";
	
    foreach($vars['options'] as $label => $option) {
        //if (!in_array($option,$vars['value'])) {
        if (is_array($vars['value'])) {
        	$valarray = $vars['value'];
        	$valarray = array_map('strtolower', $valarray);
        	if (!in_array(strtolower($option),$valarray)) {
	            $selected = "";
	        } else {
	            $selected = "checked = \"checked\"";
	        }
        } else {
	    	if (strtolower($option) != strtolower($vars['value'])) {
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
        if ($vars['disabled']) $disabled = ' disabled="yes" '; 
        echo "<label><input type=\"checkbox\" $disabled {$vars['js']} name=\"{$vars['internalname']}[]\" {$selected} value=\"".htmlentities($option, ENT_QUOTES, 'UTF-8')."\" {$selected} class=\"$class\" />{$label}</label><br />";
    }

?> 