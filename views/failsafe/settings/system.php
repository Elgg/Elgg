<?php

	/**
	 * Elgg system settings form
	 * The form to change system settings
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['action'] If set, the place to forward the form to (usually action/systemsettings/save)
	 */

	// Set action appropriately
		if (!isset($vars['action'])) {
			$action = $vars['url'] . "action/systemsettings/save";
		} else {
			$action = $vars['action'];
		}
		
		$form_body = "";
		foreach(array('sitename','sitedescription', 'wwwroot','path','dataroot', 'view') as $field) {
			$form_body .= "<p>";
			$form_body .= elgg_echo($field) . "<br />";
			$form_body .= elgg_view("input/text",array('internalname' => $field, 'value' => $vars['config']->$field));
			$form_body .= "</p>";
		}
		
		$languages = get_installed_translations();
		$form_body .= "<p>" . elgg_echo('language') . elgg_view("input/pulldown", array('internalname' => 'language', 'value' => $vars['config']->language, 'options_values' => $languages)) . "</p>";
		
		$form_body .= "<p class=\"admin_debug\">" . elgg_echo('debug') . "<br />" .elgg_view("input/checkboxes", array('options' => array(elgg_echo('debug:label')), 'internalname' => 'debug', 'value' => ($vars['config']->debug ? elgg_echo('debug:label') : "") )) . "</p>";
		
		$form_body .= "<p class=\"admin_usage\">" . elgg_echo('usage') . "<br />";
		$on = elgg_echo('usage:label');

		if (isset($CONFIG->ping_home))
			$on = ($vars['config']->ping_home!='disabled' ? elgg_echo('usage:label') : "");
		$form_body .= elgg_view("input/checkboxes", array('options' => array(elgg_echo('usage:label')), 'internalname' => 'usage', 'value' => $on )); 
		$form_body .= "</p>";
		
		$form_body .= elgg_view('input/hidden', array('internalname' => 'settings', 'value' => 'go'));
		
		$form_body .= elgg_view('input/submit', array('value' => elgg_echo("save")));
		
		echo elgg_view('input/form', array('action' => $action, 'body' => $form_body));
		
?>