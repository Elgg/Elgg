<?php
	/**
	 * Parameter input functions.
	 * This file contains functions for getting input from get/post variables.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Get some input from passed variables.
	 * 
	 * @param $variable string The variable we want to return.
	 * @param $default mixed A default value for the variable if it is not found.
	 */
	function get_input($variable, $default = "")
	{
		
		
		// TODO: Some nice filtering here

		if (isset($_REQUEST[$variable]))
			return trim($_REQUEST[$variable]);
		
		return $default;
	}
?>