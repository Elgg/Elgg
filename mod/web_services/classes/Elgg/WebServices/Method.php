<?php

namespace Elgg\WebServices;

/**
 * Wrapper class for authenticating and executing API methods
 *
 * @property string $method            Method name, e.g. 'system.api.list'
 * @property string $function          Callable used to execute this method
 * @property string $description       Description of this method
 * @property bool   $require_api_auth  Does this method require API authentication
 * @property bool   $require_user_auth Does this method require user authentication
 * @property array  $parameters        Method parameter declarations
 * @property string $call_method       HTTP methods that should be used to invoke this method
 * @property bool   $assoc             Pass parameters to callback as an associative array
 */
class Method {

	/**
	 * Constructs a new method from an array of params
	 *
	 * @param array $params Method parameters
	 * 			<code>
	 * 			array (
	 *						"method" => "my.method",
	 * 						"description" => "Some human readable description"
	 * 						"function" = 'my_function_callback'
	 * 						"parameters" = array (
	 * 							"variable" = array ( // the order should be the same as the function callback
	 * 								type => 'int' | 'bool' | 'float' | 'string' | 'array'
	 * 								required => true (default) | false
	 * 								default => value // optional
	 * 							)
	 * 						)
	 * 						"call_method" = 'GET' | 'POST'
	 * 						"require_api_auth" => true | false (default)
	 * 						"require_user_auth" => true | false (default)
	 *						"assoc" => true | false (default)
	 * 					);
	 * 			</code>
	 * @return Elgg\WebServices\Method
	 */
	public static function factory(array $params = array()) {

		$api_method = new Method();
		$api_method->method = elgg_extract('method', $params);
		$api_method->description = elgg_extract('description', $params);
		$api_method->function = elgg_extract('function', $params);
		$api_method->require_api_auth = (bool) elgg_extract('require_api_auth', $params, true);
		$api_method->require_user_auth = (bool) elgg_extract('require_user_auth', $params, false);
		$api_method->call_method = strtoupper((string) elgg_extract('call_method', $params, 'GET'));
		$api_method->parameters = (array) elgg_extract('parameters', $params, array());
		$api_method->assoc = elgg_extract('assoc', $params, false);
		
		if (!$api_method->method || !$api_method->function) {
			$msg = elgg_echo('InvalidParameterException:APIMethodOrFunctionNotSet');
			throw new \InvalidParameterException($msg);
		}

		if (!is_array($api_method->parameters)) {
			$msg = elgg_echo('InvalidParameterException:APIParametersArrayStructure', array($api_method->method));
			throw new \InvalidParameterException($msg);
		}

		foreach ($api_method->parameters as $key => $value) {
			// Verify that method parameters are defined with an explicit type
			if (empty($value['type'])) {
				$msg = elgg_echo('APIException:InvalidParameter', array($key, $api_method->method));
				throw new \APIException($msg);
			}

			// If required flag is missing, parameter is assumed required
			$api_method->parameters[$key]['required'] = (bool) elgg_extract('required', $value, true);
		}

		if (!in_array($api_method->call_method, array('GET', 'POST'))) {
			$msg = elgg_echo('InvalidParameterException:UnrecognisedHttpMethod', array($api_method->call_method, $api_method->method));
			throw new \InvalidParameterException($msg);
		}

		return $api_method;
	}

	/**
	 * Executes a method.
	 * A method is a function which has previously exposed using {@link elgg_ws_expose_function()}
	 *
	 * @see elgg_ws_expose_function()
	 * @see _php_api_exception_handler()
	 *
	 * @param string $this->method Method, e.g. "system.api.list"
	 *
	 * @return GenericResult The result of the execution.
	 * @throws APIException|CallException
	 * @since 2.0
	 * @access private
	 */
	public function execute() {
		$this->authenticate();

		if (!is_callable($this->function)) {
			// Method callback function does not exist or not callable
			$msg = elgg_echo('APIException:FunctionDoesNotExist', array($this->method));
			throw new \APIException($msg);
		}

		if (strcmp(_elgg_services()->request->server->get('REQUEST_METHOD'), $this->call_method) != 0) {
			$msg = elgg_echo('CallException:InvalidCallMethod', array($this->method, $this->call_method));
			throw new \CallException($msg);
		}

		$parameters = $this->getParams();
		if ($this->assoc) {
			$result = call_user_func($this->function, $parameters);
		} else {
			$result = call_user_func_array($this->function, $parameters);
		}

		// Sanity check result
		if ($result instanceof \GenericResult) {
			// If this function returns an api result itself, just return it
			return $result;
		} else if ($result === false) {
			// Function returns false or there is a call error
			$msg = elgg_echo('APIException:FunctionCallError', array($this->function, $this->method, print_r($parameters, true)));
			throw new \APIException($msg);
		} else if ($result === NULL) {
			// If no value
			$msg = elgg_echo('APIException:FunctionNoReturn', array($this->function, print_r($parameters, true)));
			throw new \APIException($msg);
		}

		// Otherwise assume that the call was successful and return it as a success object.
		return \SuccessResult::getInstance($result);
	}

	/**
	 * Check that the method call has the proper API and user authentication
	 *
	 * @return true
	 * @throws APIException
	 * @since 2.0
	 * @access private
	 */
	protected function authenticate() {

		// check API authentication if required
		if ($this->require_api_auth == true) {
			$api_pam = new \ElggPAM('api');
			if ($api_pam->authenticate() !== true) {
				throw new \APIException(elgg_echo('APIException:APIAuthenticationFailed'));
			}
		}

		$user_pam = new \ElggPAM('user');
		$user_auth_result = $user_pam->authenticate(array());

		// check if user authentication is required
		if ($this->require_user_auth == true) {
			if ($user_auth_result == false) {
				throw new \APIException($user_pam->getFailureMessage(), \ErrorResult::$RESULT_FAIL_AUTHTOKEN);
			}
		}

		return true;
	}

	/**
	 * This function analyses all expected parameters of a given method,
	 * and builds an array of key => value pairs, where the value is a sanitized
	 * representation of the input cast to the value type specified by the method
	 * specification.
	 *
	 * For unknown value types, or casting errors, an exception will be thrown
	 * For required parameters without input values, an exception will be thrown
	 *
	 * @return array
	 * @throws APIException
	 * @since 2.0
	 * @access private
	 */
	protected function getParams() {

		$sanitised = array();

		$expected_parameters = $this->parameters;

		if (empty($expected_parameters)) {
			// This method has no expected parameters
			return $sanitised;
		}

		foreach ($expected_parameters as $key => $spec) {
			// Make things go through the sanitiser
			$default = elgg_extract('default', $spec);
			$value = get_input($key, $default);

			// Cast values to specified type
			$type = elgg_extract('type', $spec);
			if (!settype($value, $type)) {
				$msg = elgg_echo('APIException:UnrecognisedTypeCast', array($type, $key, $this->method));
				throw new \APIException($msg);
			}

			// Validate required values
			$required = elgg_extract('required', $spec);
			if ($required) {
				if (($type == 'array' && empty($value)) || $value == '' || $value == null) {
					$msg = elgg_echo('APIException:MissingParameterInMethod', array($key, $this->method));
					throw new \APIException($msg);
				}
			}

			$sanitised[$key] = $value;
		}

		return $sanitised;
	}

}
