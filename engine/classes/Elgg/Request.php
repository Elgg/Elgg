<?php

/**
 * Simplified representation of an HTTP request.
 * 
 * @access private
 */
class Elgg_Request {
	private $input = array();
	
	/**
	 * @param ElggPluginHookService $hooks    For plugin-customizable input filtering.
	 * @param array                 $_SERVER  An array that conforms to the $_SERVER api.
	 * @param array                 $_REQUEST An array that conforms to the $_REQUEST api.
	 */
	public function __construct(ElggPluginHookService $hooks, array $_SERVER, array $_REQUEST = array()) {
		$this->hooks = $hooks;
		$this->server = $_SERVER;
		$this->request = $_REQUEST;
	}

	/**
	 * @return string The path relative to the site root. Includes leading slash.
	 */
	public function getPath() {
		return $this->server['REQUEST_URI'];
	}

	/**
	 * @param string $name  The input key.
	 * @param mixed  $value The input value.
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
	 * TODO(evan): Move to ElggPluginHookService?
	 * @param string $var The HTML string to filter.
	 * 
	 * @return string The filtered HTML input.
	 */
	public function filterTags($var) {
		return $this->hooks->trigger('validate', 'input', null, $var);
	}
}