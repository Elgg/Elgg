<?php

namespace Elgg\WebServices\Di;

use Elgg\Traits\Di\ServiceFacade;
use Elgg\WebServices\ApiMethod;

/**
 * Registration service for api endpoints (methods)
 *
 * @since 4.0
 */
class ApiRegistrationService {
	
	use ServiceFacade;
	
	/**
	 * @var ApiRegistrationCollection
	 */
	protected $collection;
	
	/**
	 * New registration service
	 *
	 * @param ApiRegistrationCollection $collection collection to store api endpoints
	 */
	public function __construct(ApiRegistrationCollection $collection) {
		$this->collection = $collection;
	}
	
	/**
	 * Expose a function as a web service.
	 *
	 * Limitations: Currently cannot expose functions which expect objects.
	 * It also cannot handle arrays of bools or arrays of arrays.
	 * Also, input will be filtered to protect against XSS attacks through the web services.
	 *
	 * Parameters should have the following format:
	 * 	"variable" = array (
	 *		type => 'int' | 'bool' | 'float' | 'string' | 'array'
	 *		required => true (default) | false
	 *		default => value (optional)
	 *	)
	 *
	 * @param string   $name              The api name to expose - for example "myapi.dosomething"
	 * @param callable $function          Callable to handle API call
	 * @param array    $params            (optional) List of parameters in the same order as in
	 *                                    your function. Default values may be set for parameters which
	 *                                    allow REST api users flexibility in what parameters are passed.
	 *                                    Generally, optional parameters should be after required
	 *                                    parameters. If an optional parameter is not set and has no default,
	 *                                    the API callable will receive null.
	 * @param string   $description       (optional) human readable description of the function.
	 * @param string   $call_method       (optional) Define what http method must be used for
	 *                                    this function. Default: GET
	 * @param bool     $require_api_auth  (optional) (default is false) Does this method
	 *                                    require API authorization? (example: API key)
	 * @param bool     $require_user_auth (optional) (default is false) Does this method
	 *                                    require user authorization?
	 * @param bool     $assoc             (optional) If set to true, the callback function will receive a single argument
	 *                                    that contains an associative array of parameter => input pairs for the method.
	 *
	 * @return void
	 */
	public function registerApiMethod(
			string $name,
			$function,
			array $params = [],
			string $description = '',
			string $call_method = 'GET',
			bool $require_api_auth = false,
			bool $require_user_auth = false,
			bool $assoc = false
		) {
		
		$api = new ApiMethod($name, $function);
		$api->params = $params;
		$api->description = $description;
		$api->call_method = $call_method;
		$api->require_api_auth = $require_api_auth;
		$api->require_user_auth = $require_user_auth;
		$api->supply_associative = $assoc;
		
		$this->collection->add($api);
	}
	
	/**
	 * Unregister a API method
	 *
	 * @param string $name name of the API method
	 *
	 * @return void
	 */
	public function unregisterApiMethod(string $name) {
		$this->collection->remove($name);
	}
	
	/**
	 * Get an API method based on it's name
	 *
	 * @param string $name name of the API method
	 *
	 * @return null|ApiMethod
	 */
	public function getApiMethod(string $name) {
		return $this->collection->get($name);
	}
	
	/**
	 * Return all registered API methods
	 *
	 * @return ApiMethod[]
	 */
	public function getAllApiMethods() {
		return $this->collection->all();
	}
	
	/**
	 * Returns registered service name
	 *
	 * @return string
	 */
	public static function name() {
		return 'webservices.api_registration';
	}
}
