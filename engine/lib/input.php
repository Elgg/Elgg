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
	 * Get some input from variables passed on the GET or POST line.
	 * 
	 * @param $variable string The variable we want to return.
	 * @param $default mixed A default value for the variable if it is not found.
	 */
	function get_input($variable, $default = "")
	{

		if (isset($_REQUEST[$variable])) {
			$value = $_REQUEST[$variable];
			return trim($_REQUEST[$variable]);
		}
		
		global $CONFIG;
		
		if (isset($CONFIG->input[$variable]))
			return $CONFIG->input[$variable];

		return $default;

	}
	
	/**
	 * Sets an input value that may later be retrieved by get_input
	 *
	 * @param string $variable The name of the variable
	 * @param string $value The value of the variable
	 */
	function set_input($variable, $value) {
		
		global $CONFIG;
		if (!isset($CONFIG->input))
			$CONFIG->input = array();
		$CONFIG->input[trim($variable)] = trim($value);
			
	}
	
	
?>