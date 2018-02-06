<?php

namespace Elgg\Http;

use Elgg\Context;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Provides unified access to the $_GET and $_POST inputs.
 *
 * @package    Elgg.Core
 * @subpackage Http
 * @since      1.10.0
 * @access     private
 */
class Input {

	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * Constructor
	 *
	 * @param Request $request Http Request object
	 */
	public function __construct(Request $request) {
		$this->request = $request;
	}

	/**
	 * Sets an input value that may later be retrieved by get_input
	 *
	 * Note: this function does not handle nested arrays (ex: form input of param[m][n])
	 *
	 * @param string          $key   The name of the variable
	 * @param string|string[] $value The value of the variable
	 *
	 * @return void
	 */
	public function set($key, $value) {
		$this->request->request->set($key, $value);
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
	 * @param string $key           The variable name we want.
	 * @param mixed  $default       A default value for the variable if it is not found.
	 * @param bool   $filter_result If true, then the result is filtered for bad tags.
	 *
	 * @return mixed
	 */
	public function get($key, $default = null, $filter_result = true) {
		$result = $default;

		$this->request->getContextStack()->push('input');

		$request = $this->request;
		$value = $request->get($key);
		if ($value !== null) {
			$result = $value;
			if ($filter_result) {
				$result = filter_tags($result);
			}
		}

		$this->request->getContextStack()->pop();

		return $result;
	}

	/**
	 * Returns all values parsed from the request
	 *
	 * @param bool $filter_result Sanitize input values
	 *
	 * @return array
	 */
	public function all($filter_result = true) {
		$query = $this->request->query->all();
		$attributes = $this->request->attributes->all();
		$post = $this->request->request->all();

		$result = array_merge($query, $attributes, $post);

		if ($filter_result) {
			$result = filter_tags($result);
		}

		return $result;
	}
}