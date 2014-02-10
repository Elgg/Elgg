<?php

/**
 * Based on Symfony2's ParameterBag.
 *
 * Copyright (c) 2004-2013 Fabien Potencier
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Container for key/values
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Http
 */
class Elgg_Http_ParameterBag implements IteratorAggregate, Countable {

	/**
	 * Parameter storage.
	 *
	 * @var array
	 */
	protected $parameters;

	/**
	 * Constructor.
	 *
	 * @param array $parameters An array of parameters
	 */
	public function __construct(array $parameters = array()) {
		$this->parameters = $parameters;
	}

	/**
	 * Returns the parameters.
	 *
	 * @return array An array of parameters
	 */
	public function all() {
		return $this->parameters;
	}

	/**
	 * Returns the parameter keys.
	 *
	 * @return array An array of parameter keys
	 */
	public function keys() {
		return array_keys($this->parameters);
	}

	/**
	 * Replaces the current parameters by a new set.
	 *
	 * @param array $parameters An array of parameters
	 * @return void
	 */
	public function replace(array $parameters = array()) {
		$this->parameters = $parameters;
	}

	/**
	 * Adds parameters.
	 *
	 * @param array $parameters An array of parameters
	 * @return void
	 */
	public function add(array $parameters = array()) {
		// original uses array_replace. using array_merge for 5.2 BC
		$this->parameters = array_merge($this->parameters, $parameters);
	}

	/**
	 * Returns a parameter by name.
	 *
	 * @param string  $path    The key
	 * @param mixed   $default The default value if the parameter key does not exist
	 * @param boolean $deep    If true, a path like foo[bar] will find deeper items
	 *
	 * @return mixed
	 *
	 * @throws InvalidArgumentException
	 */
	public function get($path, $default = null, $deep = false) {
		if (!$deep || false === $pos = strpos($path, '[')) {
			return array_key_exists($path, $this->parameters) ? $this->parameters[$path] : $default;
		}

		$root = substr($path, 0, $pos);
		if (!array_key_exists($root, $this->parameters)) {
			return $default;
		}

		$value = $this->parameters[$root];
		$currentKey = null;
		for ($i = $pos, $c = strlen($path); $i < $c; $i++) {
			$char = $path[$i];

			if ('[' === $char) {
				if (null !== $currentKey) {
					throw new InvalidArgumentException(sprintf('Malformed path. Unexpected "[" at position %d.', $i));
				}

				$currentKey = '';
			} elseif (']' === $char) {
				if (null === $currentKey) {
					throw new InvalidArgumentException(sprintf('Malformed path. Unexpected "]" at position %d.', $i));
				}

				if (!is_array($value) || !array_key_exists($currentKey, $value)) {
					return $default;
				}

				$value = $value[$currentKey];
				$currentKey = null;
			} else {
				if (null === $currentKey) {
					throw new InvalidArgumentException(sprintf('Malformed path. Unexpected "%s" at position %d.', $char, $i));
				}

				$currentKey .= $char;
			}
		}

		if (null !== $currentKey) {
			throw new InvalidArgumentException(sprintf('Malformed path. Path must end with "]".'));
		}

		return $value;
	}

	/**
	 * Sets a parameter by name.
	 *
	 * @param string $key   The key
	 * @param mixed  $value The value
	 * @return void
	 */
	public function set($key, $value) {
		$this->parameters[$key] = $value;
	}

	/**
	 * Returns true if the parameter is defined.
	 *
	 * @param string $key The key
	 *
	 * @return boolean true if the parameter exists, false otherwise
	 */
	public function has($key) {
		return array_key_exists($key, $this->parameters);
	}

	/**
	 * Removes a parameter.
	 *
	 * @param string $key The key
	 * @return void
	 */
	public function remove($key) {
		unset($this->parameters[$key]);
	}

	/**
	 * Returns the alphabetic characters of the parameter value.
	 *
	 * @param string  $key     The parameter key
	 * @param mixed   $default The default value if the parameter key does not exist
	 * @param boolean $deep    If true, a path like foo[bar] will find deeper items
	 *
	 * @return string The filtered value
	 */
	public function getAlpha($key, $default = '', $deep = false) {
		return preg_replace('/[^[:alpha:]]/', '', $this->get($key, $default, $deep));
	}

	/**
	 * Returns the alphabetic characters and digits of the parameter value.
	 *
	 * @param string  $key     The parameter key
	 * @param mixed   $default The default value if the parameter key does not exist
	 * @param boolean $deep    If true, a path like foo[bar] will find deeper items
	 *
	 * @return string The filtered value
	 */
	public function getAlnum($key, $default = '', $deep = false) {
		return preg_replace('/[^[:alnum:]]/', '', $this->get($key, $default, $deep));
	}

	/**
	 * Returns the digits of the parameter value.
	 *
	 * @param string  $key     The parameter key
	 * @param mixed   $default The default value if the parameter key does not exist
	 * @param boolean $deep    If true, a path like foo[bar] will find deeper items
	 *
	 * @return string The filtered value
	 */
	public function getDigits($key, $default = '', $deep = false) {
		// we need to remove - and + because they're allowed in the filter
		return str_replace(array('-', '+'), '', $this->filter($key, $default, $deep, FILTER_SANITIZE_NUMBER_INT));
	}

	/**
	 * Returns the parameter value converted to integer.
	 *
	 * @param string  $key     The parameter key
	 * @param mixed   $default The default value if the parameter key does not exist
	 * @param boolean $deep    If true, a path like foo[bar] will find deeper items
	 *
	 * @return integer The filtered value
	 */
	public function getInt($key, $default = 0, $deep = false) {
		return (int) $this->get($key, $default, $deep);
	}

	/**
	 * Filter key.
	 *
	 * @param string  $key     Key.
	 * @param mixed   $default Default = null.
	 * @param boolean $deep    Default = false.
	 * @param integer $filter  FILTER_* constant.
	 * @param mixed   $options Filter options.
	 *
	 * @see http://php.net/manual/en/function.filter-var.php
	 *
	 * @return mixed
	 */
	public function filter($key, $default = null, $deep = false, $filter = FILTER_DEFAULT, $options = array()) {
		$value = $this->get($key, $default, $deep);

		// Always turn $options into an array - this allows filter_var option shortcuts.
		if (!is_array($options) && $options) {
			$options = array('flags' => $options);
		}

		// Add a convenience check for arrays.
		if (is_array($value) && !isset($options['flags'])) {
			$options['flags'] = FILTER_REQUIRE_ARRAY;
		}

		return filter_var($value, $filter, $options);
	}

	/**
	 * Returns an iterator for parameters.
	 *
	 * @return ArrayIterator
	 */
	public function getIterator() {
		return new ArrayIterator($this->parameters);
	}

	/**
	 * Returns the number of parameters.
	 *
	 * @return int The number of parameters
	 */
	public function count() {
		return count($this->parameters);
	}

}
