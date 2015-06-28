<?php

namespace Elgg\WebServices;

/**
 * API method registry
 */
class Registry {

	/**
	 * A registry of methods
	 * @var array
	 */
	private $methods = array();

	/**
	 * Adds a method to registry
	 *
	 * @param \Elgg\WebServices\Method $method Method object
	 * @return boolean
	 */
	public function register(\Elgg\WebServices\Method $method) {
		$this->methods[$method->method] = $method;
		return true;
	}

	/**
	 * Removes method from the register
	 * 
	 * @param string $method      Method name
	 * @return bool
	 */
	public function unregister($method) {
		unset($this->methods[$method]);
		return true;
	}

	/**
	 * Returns a method object
	 *
	 * @param string $method      Method name
	 * @return \Elgg\WebServices\Method
	 * @throws APIException
	 */
	public function get($method = '') {
		if (!isset($this->methods[$method])) {
			// Method has not been exposed
			$msg = elgg_echo('APIException:MethodCallNotImplemented', array($method));
			throw new \APIException($msg);
		}
		return $this->methods[$method];
	}

	/**
	 * Returns all registered methods
	 * @return array
	 */
	public function all() {
		return (array) $this->methods;
	}

	/**
	 * Creates a new API User
	 *
	 * @param int $site_guid GUID of the site
	 * @return \Elgg\WebServices\ApiUser|false
	 */
	public function createApiUser($site_guid) {
		return (new \Elgg\WebServices\KeyService($site_guid))->create();
	}

	/**
	 * Retrieves API user details from public API key
	 *
	 * @param int    $site_guid GUID of the site entity
	 * @param string $api_key   Public API key
	 * @return \Elgg\WebServices\ApiUser|false
	 */
	public function getApiUser($site_guid, $api_key) {
		return (new \Elgg\WebServices\KeyService($site_guid))->get($api_key);
	}

	/**
	 * Deletes API User
	 *
	 * @param int $site_guid GUID of the site
	 * @return bool
	 */
	public function removeApiUser($site_guid, $api_key) {
		return (new \Elgg\WebServices\KeyService($site_guid))->revoke($api_key);
	}
}
