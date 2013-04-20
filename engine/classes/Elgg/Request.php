<?php

/**
 * Simplified representation of an HTTP request.
 * 
 * Plugin devs should use these wrapper functions:
 *  * set_input
 *  * get_input
 *  * filter_tags
 * 
 * @package    Elgg.Core
 * @subpackage Request
 * @since      1.9.0
 * @access private
 */
class Elgg_Request {
	private $input = array();
	private $hooks;
	private $server;
	private $request;
	
	/**
	 * Constructor
	 * 
	 * @param Elgg_PluginHookService $hooks   For plugin-customizable input filtering.
	 * @param array                  $server  An array that conforms to the $_SERVER api.
	 * @param array                  $request An array that conforms to the $_REQUEST api.
	 */
	public function __construct(Elgg_PluginHookService $hooks, array $server, array $request = array()) {
		$this->hooks = $hooks;
		$this->server = $server;
		$this->request = $request;
	}

	/**
	 * Get the currently requested URL path.
	 * 
	 * @return string The path relative to the site root. Includes leading slash.
	 */
	public function getPath() {
		return $this->server['REQUEST_URI'];
	}

	/**
	 * Force an input parameter to be a certain value.
	 * 
	 * @param string $name  The input key.
	 * @param mixed  $value The input value.
	 * 
	 * @return void
	 */
	public function setInput($name, $value) {
		if (is_array($value)) {
			array_walk_recursive($value, create_function('&$v, $k', '$v = trim($v);'));
			$this->input[trim($name)] = $value;
		} else {
			$this->input[trim($name)] = trim($value);
		}
	}
	
	/**
	 * Get the current value of a given input parameter.
	 * 
	 * @param string  $name          The input key.
	 * @param mixed   $default       The default value of the input if not yet set.
	 * @param boolean $filter_result Whether to send input through HTML filtering.
	 * 
	 * @return mixed The value of the input.
	 */
	public function getInput($name, $default = NULL, $filter_result = TRUE) {
		$result = $default;
	
		elgg_push_context('input');
	
		if (isset($this->input[$name])) {
			$result = $this->input[$name];
	
			if ($filter_result) {
				$result = $this->filterTags($result);
			}
		} elseif (isset($this->request[$name])) {
			if (is_array($this->request[$name])) {
				$result = $this->request[$name];
			} else {
				$result = trim($this->request[$name]);
			}
	
			if ($filter_result) {
				$result = $this->filterTags($result);
			}
		}
	
		elgg_pop_context();
	
		return $result;
	}
	
	/**
	 * Filter some user-provided HTML for XSS, etc.
	 * 
	 * TODO(evan): Move to Elgg_PluginHookService?
	 * 
	 * @param string $var The HTML string to filter.
	 * 
	 * @return string The filtered HTML input.
	 */
	public function filterTags($var) {
		return $this->hooks->trigger('validate', 'input', null, $var);
	}
}