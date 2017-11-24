<?php
/**
 * Elgg web services API library
 * Functions and objects for exposing custom web services.
 *
 */

/**
 * Check that the method call has the proper API and user authentication
 *
 * @param string $method The api name that was exposed
 *
 * @return true or throws an exception
 * @throws APIException
 * @since 1.7.0
 * @access private
 */
function authenticate_method($method) {
	global $API_METHODS;

	// method must be exposed
	if (!isset($API_METHODS[$method])) {
		throw new APIException(elgg_echo('APIException:MethodCallNotImplemented', [$method]));
	}

	// check API authentication if required
	if ($API_METHODS[$method]["require_api_auth"] == true) {
		$api_pam = new ElggPAM('api');
		if ($api_pam->authenticate() !== true) {
			throw new APIException(elgg_echo('APIException:APIAuthenticationFailed'));
		}
	}

	$user_pam = new ElggPAM('user');
	$user_auth_result = $user_pam->authenticate([]);

	// check if user authentication is required
	if ($API_METHODS[$method]["require_user_auth"] == true) {
		if ($user_auth_result == false) {
			throw new APIException($user_pam->getFailureMessage(), ErrorResult::$RESULT_FAIL_AUTHTOKEN);
		}
	}

	return true;
}

/**
 * Executes a method.
 * A method is a function which you have previously exposed using expose_function.
 *
 * @param string $method Method, e.g. "foo.bar"
 *
 * @return GenericResult The result of the execution.
 * @throws APIException|CallException
 * @access private
 */
function execute_method($method) {
	global $API_METHODS;

	// method must be exposed
	if (!isset($API_METHODS[$method])) {
		$msg = elgg_echo('APIException:MethodCallNotImplemented', [$method]);
		throw new APIException($msg);
	}

	// function must be callable
	$function = elgg_extract('function', $API_METHODS[$method]);
	if (!$function || !is_callable($function)) {
		$msg = elgg_echo('APIException:FunctionDoesNotExist', [$method]);
		throw new APIException($msg);
	}

	// check http call method
	if (strcmp(get_call_method(), $API_METHODS[$method]["call_method"]) != 0) {
		$msg = elgg_echo('CallException:InvalidCallMethod', [$method,
		$API_METHODS[$method]["call_method"]]);
		throw new CallException($msg);
	}

	$parameters = get_parameters_for_method($method);

	// may throw exception, which is not caught here
	verify_parameters($method, $parameters);

	$serialised_parameters = serialise_parameters($method, $parameters);

	// Execute function: Construct function and calling parameters
	$serialised_parameters = trim($serialised_parameters, ", ");

	// Sadly we probably can't get rid of this eval() in 2.x. Doing so would involve
	// replacing serialise_parameters(), which does a bunch of weird stuff we need to
	// stay BC with 2.x. There are tests for a lot of these quirks in ElggCoreWebServicesApiTest
	// particularly in testSerialiseParametersCasting().
	$arguments = eval("return [$serialised_parameters];");

	if ($API_METHODS[$method]['assoc']) {
		$argument = array_combine(_elgg_ws_get_parameter_names($method), $arguments);
		$result = call_user_func($function, $argument);
	} else {
		$result = call_user_func_array($function, $arguments);
	}

	$result = elgg_trigger_plugin_hook('rest:output', $method, $parameters, $result);
	
	// Sanity check result
	// If this function returns an api result itself, just return it
	if ($result instanceof GenericResult) {
		return $result;
	}

	if ($result === false) {
		$msg = elgg_echo('APIException:FunctionParseError', [$function, $serialised_parameters]);
		throw new APIException($msg);
	}

	if ($result === null) {
		// If no value
		$msg = elgg_echo('APIException:FunctionNoReturn', [$function, $serialised_parameters]);
		throw new APIException($msg);
	}

	// Otherwise assume that the call was successful and return it as a success object.
	return SuccessResult::getInstance($result);
}

/**
 * Get the request method.
 *
 * @return string HTTP request method
 * @access private
 */
function get_call_method() {
	return _elgg_services()->request->server->get('REQUEST_METHOD');
}

/**
 * This function analyses all expected parameters for a given method
 *
 * This function sanitizes the input parameters and returns them in
 * an associated array.
 *
 * @param string $method The method
 *
 * @return array containing parameters as key => value
 * @access private
 */
function get_parameters_for_method($method) {
	global $API_METHODS;

	$sanitised = [];

	// if there are parameters, sanitize them
	if (isset($API_METHODS[$method]['parameters'])) {
		foreach ($API_METHODS[$method]['parameters'] as $k => $v) {
			$param = get_input($k); // Make things go through the sanitiser
			if ($param !== '' && $param !== null) {
				$sanitised[$k] = $param;
			} else {
				// parameter wasn't passed so check for default
				if (isset($v['default'])) {
					$sanitised[$k] = $v['default'];
				}
			}
		}
	}

	return $sanitised;
}

/**
 * Get POST data
 * Since this is called through a handler, we need to manually get the post data
 *
 * @return POST data as string encoded as multipart/form-data
 * @access private
 */
function get_post_data() {

	$postdata = file_get_contents('php://input');

	return $postdata;
}

/**
 * Verify that the required parameters are present
 *
 * @param string $method     Method name
 * @param array  $parameters List of expected parameters
 *
 * @return true on success or exception
 * @throws APIException
 * @since 1.7.0
 * @access private
 */
function verify_parameters($method, $parameters) {
	global $API_METHODS;

	// are there any parameters for this method
	if (!(isset($API_METHODS[$method]["parameters"]))) {
		return true; // no so return
	}

	// check that the parameters were registered correctly and all required ones are there
	foreach ($API_METHODS[$method]['parameters'] as $key => $value) {
		// this tests the expose structure: must be array to describe parameter and type must be defined
		if (!is_array($value) || !isset($value['type'])) {
			$msg = elgg_echo('APIException:InvalidParameter', [$key, $method]);
			throw new APIException($msg);
		}

		// Check that the variable is present in the request if required
		if ($value['required'] && !array_key_exists($key, $parameters)) {
			$msg = elgg_echo('APIException:MissingParameterInMethod', [$key, $method]);
			throw new APIException($msg);
		}
	}

	return true;
}

/**
 * Get the names of a method's parameters
 *
 * @param string $method the api method to get the params for
 * @return string[]
 * @access private
 */
function _elgg_ws_get_parameter_names($method) {
	global $API_METHODS;

	if (!isset($API_METHODS[$method]["parameters"])) {
		return [];
	}

	return array_keys($API_METHODS[$method]["parameters"]);
}

/**
 * Serialize an array of parameters for an API method call, applying transformations
 * to values depending on the declared parameter type, and returning a string of PHP
 * code representing the contents of a PHP array literal.
 *
 * A leading comma needs to be removed from the output.
 *
 * @see \ElggCoreWebServicesApiTest::testSerialiseParametersCasting
 *
 * @param string $method     API method name
 * @param array  $parameters Array of parameters
 *
 * @return string or exception E.g. ',"foo",2.1'
 * @throws APIException
 * @since 1.7.0
 * @access private
 *
 * @todo in 3.0 this should return an array of parameter values instead of a string of code.
 */
function serialise_parameters($method, $parameters) {
	global $API_METHODS;

	// are there any parameters for this method
	if (!(isset($API_METHODS[$method]["parameters"]))) {
		return ''; // if not, return
	}

	$serialised_parameters = "";
	foreach ($API_METHODS[$method]['parameters'] as $key => $value) {
		// avoid warning on parameters that are not required and not present
		if (!isset($parameters[$key])) {
			$serialised_parameters .= ',null';
			continue;
		}

		// Set variables casting to type.
		switch (strtolower($value['type'])) {
			case 'int':
			case 'integer' :
				$serialised_parameters .= "," . (int) trim($parameters[$key]);
				break;
			case 'bool':
			case 'boolean':
				// change word false to boolean false
				if (strcasecmp(trim($parameters[$key]), "false") == 0) {
					$serialised_parameters .= ',false';
				} else if ($parameters[$key] == 0) {
					$serialised_parameters .= ',false';
				} else {
					$serialised_parameters .= ',true';
				}

				break;
			case 'string':
				$serialised_parameters .= ',' . var_export(trim($parameters[$key]), true);
				break;
			case 'float':
				$serialised_parameters .= "," . (float) trim($parameters[$key]);
				break;
			case 'array':
				// we can handle an array of strings, maybe ints, definitely not booleans or other arrays
				if (!is_array($parameters[$key])) {
					$msg = elgg_echo('APIException:ParameterNotArray', [$key]);
					throw new APIException($msg);
				}

				$array = "array(";

				foreach ($parameters[$key] as $k => $v) {
					// This is using sanitise_string() to escape characters to be inside a
					// single-quoted string literal in PHP code. Not sure what we have to do
					// to keep this safe in 3.0...
					$k = sanitise_string($k);
					$v = sanitise_string($v);

					$array .= "'$k'=>'$v',";
				}

				$array = trim($array, ",");

				$array .= ")";
				$array = ",$array";

				$serialised_parameters .= $array;
				break;
			default:
				$msg = elgg_echo('APIException:UnrecognisedTypeCast', [$value['type'], $key, $method]);
				throw new APIException($msg);
		}
	}

	return $serialised_parameters;
}

// API authorization handlers /////////////////////////////////////////////////////////////////////

/**
 * PAM: Confirm that the call includes a valid API key
 *
 * @return bool true if good API key - otherwise throws exception
 * @throws APIException
 * @since 1.7.0
 * @access private
 */
function api_auth_key() {
	// check that an API key is present
	$api_key = get_input('api_key');
	if ($api_key == "") {
		throw new APIException(elgg_echo('APIException:MissingAPIKey'));
	}

	// check that it is active
	$api_user = get_api_user(elgg_get_site_entity()->guid, $api_key);
	if (!$api_user) {
		// key is not active or does not exist
		throw new APIException(elgg_echo('APIException:BadAPIKey'));
	}

	// can be used for keeping stats
	// plugin can also return false to fail this authentication method
	return elgg_trigger_plugin_hook('api_key', 'use', $api_key, true);
}

/**
 * PAM: Confirm the HMAC signature
 *
 * @return true if success - otherwise throws exception
 *
 * @throws SecurityException
 * @since 1.7.0
 * @access private
 */
function api_auth_hmac() {
	// Get api header
	$api_header = get_and_validate_api_headers();

	// Pull API user details
	$api_user = get_api_user(elgg_get_site_entity()->guid, $api_header->api_key);

	if (!$api_user) {
		throw new SecurityException(elgg_echo('SecurityException:InvalidAPIKey'),
		ErrorResult::$RESULT_FAIL_APIKEY_INVALID);
	}

	// Get the secret key
	$secret_key = $api_user->secret;

	// get the query string
	$query = _elgg_services()->request->server->get('REQUEST_URI');
	$query = substr($query, strpos($query, '?') + 1);

	// calculate expected HMAC
	$hmac = calculate_hmac(	$api_header->hmac_algo,
							$api_header->time,
							$api_header->nonce,
							$api_header->api_key,
							$secret_key,
							$query,
							$api_header->method == 'POST' ? $api_header->posthash : "");


	if ($api_header->hmac !== $hmac) {
		throw new SecurityException("HMAC is invalid.  {$api_header->hmac} != [calc]$hmac");
	}

	// Now make sure this is not a replay
	if (cache_hmac_check_replay($hmac)) {
		throw new SecurityException(elgg_echo('SecurityException:DupePacket'));
	}

	// Validate post data
	if ($api_header->method == "POST") {
		$postdata = get_post_data();
		$calculated_posthash = calculate_posthash($postdata, $api_header->posthash_algo);

		if (strcmp($api_header->posthash, $calculated_posthash) != 0) {
			$msg = elgg_echo('SecurityException:InvalidPostHash',
			[$calculated_posthash, $api_header->posthash]);

			throw new SecurityException($msg);
		}
	}

	return true;
}

// HMAC /////////////////////////////////////////////////////////////////////

/**
 * This function extracts the various header variables needed for the HMAC PAM
 *
 * @return stdClass Containing all the values.
 * @throws APIException Detailing any error.
 * @access private
 */
function get_and_validate_api_headers() {
	$result = new stdClass;

	$result->method = get_call_method();
	// Only allow these methods
	if (($result->method != "GET") && ($result->method != "POST")) {
		throw new APIException(elgg_echo('APIException:NotGetOrPost'));
	}

	$server = _elgg_services()->request->server;

	$result->api_key = $server->get('HTTP_X_ELGG_APIKEY');
	if ($result->api_key == "") {
		throw new APIException(elgg_echo('APIException:MissingAPIKey'));
	}

	$result->hmac = $server->get('HTTP_X_ELGG_HMAC');
	if ($result->hmac == "") {
		throw new APIException(elgg_echo('APIException:MissingHmac'));
	}

	$result->hmac_algo = $server->get('HTTP_X_ELGG_HMAC_ALGO');
	if ($result->hmac_algo == "") {
		throw new APIException(elgg_echo('APIException:MissingHmacAlgo'));
	}

	$result->time = $server->get('HTTP_X_ELGG_TIME');
	if ($result->time == "") {
		throw new APIException(elgg_echo('APIException:MissingTime'));
	}

	// Must have been sent within 25 hour period.
	// 25 hours is more than enough to handle server clock drift.
	// This values determines how long the HMAC cache needs to store previous
	// signatures. Heavy use of HMAC is better handled with a shorter sig lifetime.
	// See cache_hmac_check_replay()
	if (($result->time < (time() - 90000)) || ($result->time > (time() + 90000))) {
		throw new APIException(elgg_echo('APIException:TemporalDrift'));
	}

	$result->nonce = $server->get('HTTP_X_ELGG_NONCE');
	if ($result->nonce == "") {
		throw new APIException(elgg_echo('APIException:MissingNonce'));
	}

	if ($result->method == "POST") {
		$result->posthash = $server->get('HTTP_X_ELGG_POSTHASH');
		if ($result->posthash == "") {
			throw new APIException(elgg_echo('APIException:MissingPOSTHash'));
		}

		$result->posthash_algo = $server->get('HTTP_X_ELGG_POSTHASH_ALGO');
		if ($result->posthash_algo == "") {
			throw new APIException(elgg_echo('APIException:MissingPOSTAlgo'));
		}

		$result->content_type = $server->get('CONTENT_TYPE');
		if ($result->content_type == "") {
			throw new APIException(elgg_echo('APIException:MissingContentType'));
		}
	}

	return $result;
}

/**
 * Map various algorithms to their PHP equivs.
 * This also gives us an easy way to disable algorithms.
 *
 * @param string $algo The algorithm
 *
 * @return string The php algorithm
 * @throws APIException if an algorithm is not supported.
 * @access private
 */
function map_api_hash($algo) {
	$algo = strtolower(sanitise_string($algo));
	$supported_algos = [
		"md5" => "md5",	// @todo Consider phasing this out
		"sha" => "sha1", // alias for sha1
		"sha1" => "sha1",
		"sha256" => "sha256"
	];

	if (array_key_exists($algo, $supported_algos)) {
		return $supported_algos[$algo];
	}

	throw new APIException(elgg_echo('APIException:AlgorithmNotSupported', [$algo]));
}

/**
 * Calculate the HMAC for the http request.
 * This function signs an api request using the information provided. The signature returned
 * has been base64 encoded and then url encoded.
 *
 * @param string $algo          The HMAC algorithm used
 * @param string $time          String representation of unix time
 * @param string $nonce         Nonce
 * @param string $api_key       Your api key
 * @param string $secret_key    Your private key
 * @param string $get_variables URLEncoded string representation of the get variable parameters,
 *                              eg "method=user&guid=2"
 * @param string $post_hash     Optional sha1 hash of the post data.
 *
 * @return string The HMAC signature
 * @access private
 */
function calculate_hmac($algo, $time, $nonce, $api_key, $secret_key,
$get_variables, $post_hash = "") {

	elgg_log("HMAC Parts: $algo, $time, $api_key, $secret_key, $get_variables, $post_hash");

	$ctx = hash_init(map_api_hash($algo), HASH_HMAC, $secret_key);

	hash_update($ctx, trim($time));
	hash_update($ctx, trim($nonce));
	hash_update($ctx, trim($api_key));
	hash_update($ctx, trim($get_variables));
	if (trim($post_hash) != "") {
		hash_update($ctx, trim($post_hash));
	}

	return urlencode(base64_encode(hash_final($ctx, true)));
}

/**
 * Calculate a hash for some post data.
 *
 * @todo Work out how to handle really large bits of data.
 *
 * @param string $postdata The post data.
 * @param string $algo     The algorithm used.
 *
 * @return string The hash.
 * @access private
 */
function calculate_posthash($postdata, $algo) {
	$ctx = hash_init(map_api_hash($algo));

	hash_update($ctx, $postdata);

	return hash_final($ctx);
}

/**
 * This function will do two things. Firstly it verifies that a HMAC signature
 * hasn't been seen before, and secondly it will add the given hmac to the cache.
 *
 * @param string $hmac The hmac string.
 *
 * @return bool True if replay detected, false if not.
 * @access private
 */
function cache_hmac_check_replay($hmac) {
	// cache lifetime is 25 hours (this should be related to the time drift
	// allowed in get_and_validate_headers
	$cache = new ElggHMACCache(90000);

	if (!$cache->load($hmac)) {
		$cache->save($hmac, $hmac);

		return false;
	}

	return true;
}

/**
 * Check the user token
 * This examines whether an authentication token is present and returns true if
 * it is present and is valid. The user gets logged in so with the current
 * session code of Elgg, that user will be logged out of all other sessions.
 *
 * @return bool
 * @access private
 */
function pam_auth_usertoken() {
	$token = get_input('auth_token');
	if (!$token) {
		return false;
	}
	
	$validated_userid = validate_user_token($token, elgg_get_site_entity()->guid);

	if ($validated_userid) {
		$u = get_entity($validated_userid);

		// Could we get the user?
		if (!$u) {
			return false;
		}

		// Not an elgg user
		if ((!$u instanceof ElggUser)) {
			return false;
		}

		// User is banned
		if ($u->isBanned()) {
			return false;
		}

		// Fail if we couldn't log the user in
		if (!login($u)) {
			return false;
		}

		return true;
	}

	return false;
}

/**
 * See if the user has a valid login sesson
 *
 * @return bool
 * @access private
 */
function pam_auth_session() {
	return elgg_is_logged_in();
}

/**
 * API PHP Error handler function.
 * This function acts as a wrapper to catch and report PHP error messages.
 *
 * @see http://uk3.php.net/set-error-handler
 *
 * @param int    $errno    Error number
 * @param string $errmsg   Human readable message
 * @param string $filename Filename
 * @param int    $linenum  Line number
 * @param array  $vars     Vars
 *
 * @return void
 * @access private
 *
 * @throws Exception
 */
function _php_api_error_handler($errno, $errmsg, $filename, $linenum, $vars) {
	global $ERRORS;

	$error = date("Y-m-d H:i:s (T)") . ": \"" . $errmsg . "\" in file "
	. $filename . " (line " . $linenum . ")";

	switch ($errno) {
		case E_USER_ERROR:
			error_log("ERROR: " . $error);
			$ERRORS[] = "ERROR: " . $error;

			// Since this is a fatal error, we want to stop any further execution but do so gracefully.
			throw new Exception("ERROR: " . $error);
			break;

		case E_WARNING :
		case E_USER_WARNING :
			error_log("WARNING: " . $error);
			$ERRORS[] = "WARNING: " . $error;
			break;

		default:
			error_log("DEBUG: " . $error);
			$ERRORS[] = "DEBUG: " . $error;
	}
}

/**
 * API PHP Exception handler.
 * This is a generic exception handler for PHP exceptions. This will catch any
 * uncaught exception, end API execution and return the result to the requestor
 * as an ErrorResult in the requested format.
 *
 * @param Exception $exception Exception
 *
 * @return void
 * @access private
 */
function _php_api_exception_handler($exception) {

	error_log("*** FATAL EXCEPTION (API) *** : " . $exception);

	$code   = $exception->getCode() == 0 ? ErrorResult::$RESULT_FAIL : $exception->getCode();
	$result = new ErrorResult($exception->getMessage(), $code, null);

	echo elgg_view_page($exception->getMessage(), elgg_view("api/output", ["result" => $result]));
}


/**
 * Services handler - turns request over to the registered handler
 * If no handler is found, this returns a 404 error
 *
 * @param string $handler Handler name
 * @param array  $request Request string
 *
 * @return void
 * @access private
 */
function service_handler($handler, $request) {
	elgg_set_context('api');

	$request = explode('/', $request);

	// after the handler, the first identifier is response format
	// ex) http://example.org/services/api/rest/json/?method=test
	$response_format = array_shift($request);
	if (!$response_format) {
		$response_format = 'json';
	}

	if (!ctype_alpha($response_format)) {
		header("HTTP/1.0 400 Bad Request");
		header("Content-type: text/plain");
		echo "Invalid format.";
		exit;
	}

	elgg_set_viewtype($response_format);

	$servicehandler = _elgg_config()->servicehandler;

	if (!isset($servicehandler) || empty($handler)) {
		// no handlers set or bad url
		header("HTTP/1.0 404 Not Found");
		exit;
	} else if (isset($servicehandler[$handler]) && is_callable($servicehandler[$handler])) {
		$function = $servicehandler[$handler];
		call_user_func($function, $request, $handler);
	} else {
		// no handler for this web service
		header("HTTP/1.0 404 Not Found");
		exit;
	}
}
