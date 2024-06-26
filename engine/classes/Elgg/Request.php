<?php

namespace Elgg;

use Elgg\Di\PublicContainer;
use Elgg\Http\Request as HttpRequest;
use Elgg\Validation\ValidationResults;

/**
 * Request container
 */
class Request {

	/**
	 * @var ValidationResults
	 */
	protected $validation;

	/**
	 * Constructor
	 *
	 * @param PublicContainer $dic          DI container
	 * @param HttpRequest     $http_request Request
	 */
	public function __construct(protected PublicContainer $dic, protected HttpRequest $http_request) {
		$this->validation = new ValidationResults();
	}

	/**
	 * Access validation results bag
	 * @return ValidationResults
	 */
	public function validation() {
		return $this->validation;
	}

	/**
	 * Get the name of the route
	 *
	 * @return string
	 */
	public function getRoute() {
		return $this->getParam('_route');
	}

	/**
	 * Get the parameters from the request query and route matches
	 *
	 * @param bool $filter Sanitize input values
	 *
	 * @return array
	 */
	public function getParams($filter = true) {
		return $this->http_request->getParams($filter);
	}

	/**
	 * Get an element of the params array. If the params array is not an array,
	 * the default will always be returned.
	 *
	 * @param string $key     The key of the value in the params array
	 * @param mixed  $default The value to return if missing
	 * @param bool   $filter  Sanitize input value
	 *
	 * @return mixed
	 */
	public function getParam($key, $default = null, $filter = true) {
		return $this->http_request->getParam($key, $default, $filter);
	}

	/**
	 * Set request parameter
	 * @see set_input()
	 *
	 * @param string $key   Parameter name
	 * @param mixed  $value Value
	 *
	 * @return void
	 */
	public function setParam($key, $value = null) {
		$this->http_request->setParam($key, $value);
	}

	/**
	 * Gets the "entity" key from the params if it holds an Elgg entity
	 *
	 * @param string $key Input key to pull entity GUID from
	 *
	 * @return \ElggEntity|null
	 */
	public function getEntityParam(string $key = 'guid'): ?\ElggEntity {
		$guid = (int) $this->http_request->getParam($key);
		if (empty($guid)) {
			return null;
		}
		
		return get_entity($guid);
	}

	/**
	 * Gets the "user" key from the params if it holds an Elgg user
	 *
	 * @param string $key Input key to pull user GUID from, or "username" for a username.
	 *
	 * @return \ElggUser|null
	 */
	public function getUserParam(string $key = 'user_guid'): ?\ElggUser {
		$prop = $this->http_request->getParam($key);
		if (elgg_is_empty($prop)) {
			return null;
		}
		
		if ($key === 'username') {
			return elgg_get_user_by_username((string) $prop);
		}

		return get_user((int) $prop);
	}

	/**
	 * Get the DI container
	 *
	 * @return PublicContainer
	 */
	public function elgg() {
		return $this->dic;
	}

	/**
	 * Get URL of the request
	 * @return string
	 */
	public function getURL() {
		return $this->http_request->getCurrentURL();
	}

	/**
	 * Get relative path of the request
	 * @return string
	 */
	public function getPath() {
		return implode('/', $this->http_request->getUrlSegments());
	}

	/**
	 * Is the route access with XmlHttpRequest
	 * @return bool
	 */
	public function isXhr() {
		return $this->http_request->isXmlHttpRequest();
	}

	/**
	 * Get HTTP method of the request
	 * @return string
	 */
	public function getMethod() {
		return $this->http_request->getMethod();
	}

	/**
	 * Get the HttpRequest for this request
	 *
	 * @return \Elgg\Http\Request
	 */
	public function getHttpRequest() {
		return $this->http_request;
	}
}
