<?php
namespace Elgg\Http;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 * 
 * Provides unified access to the $_GET and $_POST inputs.
 *
 * @package    Elgg.Core
 * @subpackage Http
 * @since      1.10.0
 * @access private
 */
class Input {
	/**
	 * Global Elgg configuration
	 * 
	 * @var \stdClass
	 */
	private $CONFIG;

	/**
	 * Constructor
	 */
	public function __construct() {
		global $CONFIG;
		$this->CONFIG = $CONFIG;
	}

	/**
	 * Sets an input value that may later be retrieved by get_input
	 *
	 * Note: this function does not handle nested arrays (ex: form input of param[m][n])
	 *
	 * @param string          $variable The name of the variable
	 * @param string|string[] $value    The value of the variable
	 *
	 * @return void
	 */
	public function set($variable, $value) {
		
		if (!isset($this->CONFIG->input)) {
			$this->CONFIG->input = array();
		}
	
		if (is_array($value)) {
			array_walk_recursive($value, create_function('&$v, $k', '$v = trim($v);'));
			$this->CONFIG->input[trim($variable)] = $value;
		} else {
			$this->CONFIG->input[trim($variable)] = trim($value);
		}
	}
	
	
	/**
	 * Get some input from variables passed submitted through GET or POST.
	 *
	 * If using any data obtained from get_input() in a web page, please be aware that
	 * it is a possible vector for a reflected XSS attack. If you are expecting an
	 * integer, cast it to an int. If it is a string, escape quotes.
	 *
	 * Note: this function does not handle nested arrays (ex: form input of param[m][n])
	 * because of the filtering done in htmlawed from the filter_tags call.
	 * @todo Is this ^ still true?
	 *
	 * @param string $variable      The variable name we want.
	 * @param mixed  $default       A default value for the variable if it is not found.
	 * @param bool   $filter_result If true, then the result is filtered for bad tags.
	 *
	 * @return mixed
	 */
	function get($variable, $default = null, $filter_result = true) {
			
		
	
		$result = $default;
	
		elgg_push_context('input');
	
		if (isset($this->CONFIG->input[$variable])) {
			// a plugin has already set this variable
			$result = $this->CONFIG->input[$variable];
			if ($filter_result) {
				$result = filter_tags($result);
			}
		} else {
			$request = _elgg_services()->request;
			$value = $request->get($variable);
			if ($value !== null) {
				$result = $value;
				if (is_string($result)) {
					// @todo why trim
					$result = trim($result);
				}
	
				if ($filter_result) {
					$result = filter_tags($result);
				}
			}
		}
	
		elgg_pop_context();
	
		return $result;

	}
}