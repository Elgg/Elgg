<?php
	/**
	 * Create a submit input button
	 * Use this view for forms rather than creating a submit/reset button tag in the wild as it provides
	 * extra security which help prevent CSRF attacks.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['value'] The current value, if any
	 * @uses $vars['js'] Any Javascript to enter into the input tag
	 * @uses $vars['internalname'] The name of the input field
	 * @uses $vars['type'] Submit or reset, defaults to submit.
	 * 
	 */

	$vars['type'] = 'submit';
	if (isset($vars['class'])) $class = $vars['class'];
	if (!$class) $class = "submit_button";
	$vars['class'] = $class;
	
	echo elgg_view('input/button', $vars);
?>