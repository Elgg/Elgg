<?php
/**
 * Elgg API
 * Functions and objects which make up the API engine.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd <info@elgg.com>
 * @link http://elgg.org/
 */

// Result classes /////////////////////////////////////////////////////////////////////////

/**
 * GenericResult Result superclass.
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Core
 */
abstract class GenericResult {
	/**
	 * The status of the result.
	 * @var int
	 */
	private $status_code;

	/**
	 * Message returned along with the status which is almost always an error message.
	 * This must be human readable, understandable and localised.
	 * @var string
	 */
	private $message;

	/**
	 * Result store.
	 * Attach result specific informaton here.
	 *
	 * @var mixed. Should probably be an object of some sort.
	 */
	private $result;

	/**
	 * Set a status code and optional message.
	 *
	 * @param int $status The status code.
	 * @param string $message The message.
	 */
	protected function setStatusCode($status, $message = "") {
		$this->status_code = $status;
		$this->message = $message;
	}

	/**
	 * Set the result.
	 *
	 * @param mixed $result
	 */
	protected function setResult($result) {
		$this->result = $result;
	}

	protected function getStatusCode() {
		return $this->status_code;
	}

	protected function getStatusMessage() {
		return $this->message;
	}

	protected function getResult() {
		return $this->result;
	}

	/**
	 * Serialise to a standard class.
	 *
	 * DEVNOTE: The API is only interested in data, we can not easily serialise
	 * custom classes without the need for 1) the other side being PHP, 2) you need to have the class
	 * definition installed, 3) its the right version!
	 *
	 * Therefore, I'm not bothering.
	 *
	 * Override this to include any more specific information, however api results should be attached to the
	 * class using setResult().
	 *
	 * if $CONFIG->debug is set then additional information about the runtime environment and authentication will be
	 * returned.
	 *
	 * @return stdClass Object containing the serialised result.
	 */
	public function export() {
		global $ERRORS, $CONFIG, $_PAM_HANDLERS_MSG;

		$result = new stdClass;

		$result->status = $this->getStatusCode();
		if ($this->getStatusMessage()!="") {
			$result->message = $this->getStatusMessage();
		}

		$resultdata = $this->getResult();
		if (isset($resultdata)) {
			$result->result = $resultdata;
		}

		if (isset($CONFIG->debug)) {
			if (count($ERRORS)) {
				$result->runtime_errors = $ERRORS;
			}

			if (count($_PAM_HANDLERS_MSG)) {
				$result->pam = $_PAM_HANDLERS_MSG;
			}
		}

		return $result;
	}
}

/**
 * SuccessResult
 * Generic success result class, extend if you want to do something special.
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Core
 */
class SuccessResult extends GenericResult {
	public static $RESULT_SUCCESS = 0;  // Do not change this from 0

	public function SuccessResult($result) {
		$this->setResult($result);
		$this->setStatusCode(SuccessResult::$RESULT_SUCCESS);
	}

	public static function getInstance($result) {
		// Return a new error object.
		return new SuccessResult($result);
	}
}

/**
 * ErrorResult
 * The error result class.
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Core
 */
class ErrorResult extends GenericResult {
	// Fail with no specific code
	public static $RESULT_FAIL = -1 ;

	public static $RESULT_FAIL_APIKEY_DISABLED = -30;
	public static $RESULT_FAIL_APIKEY_INACTIVE = -31;
	public static $RESULT_FAIL_APIKEY_INVALID = -32;

	// Invalid, expired or missing auth token
	public static $RESULT_FAIL_AUTHTOKEN = -20;

	public function ErrorResult($message, $code = "", Exception $exception = NULL) {
		if ($code == "") {
			$code = ErrorResult::$RESULT_FAIL;
		}

		if ($exception!=NULL) {
			$this->setResult($exception->__toString());
		}

		$this->setStatusCode($code, $message);
	}

	/**
	 * Get a new instance of the ErrorResult.
	 *
	 * @param string $message
	 * @param int $code
	 * @param Exception $exception Optional exception for generating a stack trace.
	 */
	public static function getInstance($message, $code = "", Exception $exception = NULL) {
		// Return a new error object.
		return new ErrorResult($message, $code, $exception);
	}
}

// Caching of HMACs ///////////////////////////////////////////////////////////////////////

/**
 * ElggHMACCache
 * Store cached data in a temporary database, only used by the HMAC stuff.
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage API
 */
class ElggHMACCache extends ElggCache {
	/**
	 * Set the Elgg cache.
	 *
	 * @param int $max_age Maximum age in seconds, 0 if no limit.
	 */
	function __construct($max_age = 0) {
		$this->set_variable("max_age", $max_age);
	}

	/**
	 * Save a key
	 *
	 * @param string $key
	 * @param string $data
	 * @return boolean
	 */
	public function save($key, $data) {
		global $CONFIG;

		$key = sanitise_string($key);
		$time = time();

		return insert_data("INSERT into {$CONFIG->dbprefix}hmac_cache (hmac, ts) VALUES ('$key', '$time')");
	}

	/**
	 * Load a key
	 *
	 * @param string $key
	 * @param int $offset
	 * @param int $limit
	 * @return string
	 */
	public function load($key, $offset = 0, $limit = null) {
		global $CONFIG;

		$key = sanitise_string($key);

		$row = get_data_row("SELECT * from {$CONFIG->dbprefix}hmac_cache where hmac='$key'");
		if ($row) {
			return $row->hmac;
		}

		return false;
	}

	/**
	 * Invalidate a given key.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function delete($key) {
		global $CONFIG;

		$key = sanitise_string($key);

		return delete_data("DELETE from {$CONFIG->dbprefix}hmac_cache where hmac='$key'");
	}

	/**
	 * Clear out all the contents of the cache.
	 *
	 * Not currently implemented in this cache type.
	 */
	public function clear() {
		return true;
	}

	/**
	 * Clean out old stuff.
	 *
	 */
	public function __destruct() {
		global $CONFIG;

		$time = time();
		$age = (int)$this->get_variable("max_age");

		$expires = $time-$age;

		delete_data("DELETE from {$CONFIG->dbprefix}hmac_cache where ts<$expires");
	}
}

// Primary Services API Server functions /////////////////////////////////////////////////////////////////////

/**
 * A global array holding API methods.
 * The structure of this is
 * 	$API_METHODS = array (
 * 		$method => array (
 * 			"description" => "Some human readable description"
 * 			"function" = 'my_function_callback'
 * 			"parameters" = array (
 * 				"variable" = array ( // NB, the order should be the same as the function callback
 * 					type => 'int' | 'bool' | 'float' | 'string'
 * 					required => true (default) | false
 * 				)
 * 			)
 * 			"call_method" = 'GET' | 'POST'
 * 			"require_api_auth" => true | false (default)
 * 			"require_user_auth" => true | false (default)
 * 		)
 *  )
 */
$API_METHODS = array();

/**
 * Expose a function as a services api call.
 *
 * Limitations: Currently cannot expose functions which expect objects.
 * It also cannot handle arrays of bools or arrays of arrays.
 * Also, input will be filtered to protect against XSS attacks through the API.
 *
 * @param string $method The api name to expose - for example "myapi.dosomething"
 * @param string $function Your function callback.
 * @param array $parameters (optional) List of parameters in the same order as in your function.
 * Default values may be set for parameters which would allow REST api users flexibility in
 * what parameters are passed. Generally, optional parameters should be after required parameters.
 * This array should be in the format
 *   "variable" = array (
 * 					type => 'int' | 'bool' | 'float' | 'string' | 'array'
 * 					required => true (default) | false
 * 					default => value (optional)
 * 	 )
 * @param string $description (optional) human readable description of the function.
 * @param string $call_method (optional) Define what http method must be used for this function. Default: GET
 * @param bool $require_api_auth (optional) (default is false) Does this method require API authorization? (example: API key)
 * @param bool $require_user_auth (optional) (default is false) Does this method require user authorization?
 * @return bool
 */
function expose_function($method, $function, array $parameters = NULL, $description = "", $call_method = "GET", $require_api_auth = false, $require_user_auth = false) {
	global $API_METHODS;

	if (($method == "") || ($function == "")) {
		throw new InvalidParameterException(elgg_echo('InvalidParameterException:APIMethodOrFunctionNotSet'));
	}

	// does not check whether this method has already been exposed - good idea?
	$API_METHODS[$method] = array();

	$API_METHODS[$method]["description"] = $description;

	// does not check whether callable - done in execute_method()
	$API_METHODS[$method]["function"] = $function;

	if ($parameters != NULL) {
		if (!is_array($parameters)) {
			throw new InvalidParameterException(sprintf(elgg_echo('InvalidParameterException:APIParametersArrayStructure'), $method));
		}

		// catch common mistake of not setting up param array correctly
		$first = current($parameters);
		if (!is_array($first)) {
			throw new InvalidParameterException(sprintf(elgg_echo('InvalidParameterException:APIParametersArrayStructure'), $method));
		}
	}

	if ($parameters != NULL) {
		// ensure the required flag is set correctly in default case for each parameter
		foreach ($parameters as $key => $value) {
			// check if 'required' was specified - if not, make it true
			if (!array_key_exists('required', $value)) {
				$parameters[$key]['required'] = true;
			}
		}

		$API_METHODS[$method]["parameters"] = $parameters;
	}

	$call_method = strtoupper($call_method);
	switch ($call_method) {
		case 'POST' :
			$API_METHODS[$method]["call_method"] = 'POST';
			break;
		case 'GET' :
			$API_METHODS[$method]["call_method"] = 'GET';
			break;
		default :
			throw new InvalidParameterException(sprintf(elgg_echo('InvalidParameterException:UnrecognisedHttpMethod'), $call_method, $method));
	}

	$API_METHODS[$method]["require_api_auth"] = $require_api_auth;

	$API_METHODS[$method]["require_user_auth"] = $require_user_auth;

	return true;
}

/**
 * Unregister an API method
 * @param string $method The api name that was exposed
 */
function unexpose_function($method) {
	global $API_METHODS;

	if (isset($API_METHODS[$method])) {
		unset($API_METHODS[$method]);
	}
}

/**
 * Check that the method call has the proper API and user authentication
 * @param string $method The api name that was exposed
 * @return true or throws an exception
 * @throws APIException
 */
function authenticate_method($method) {
	global $API_METHODS;

	// method must be exposed
	if (!isset($API_METHODS[$method])) {
		throw new APIException(sprintf(elgg_echo('APIException:MethodCallNotImplemented'), $method));
	}

	// make sure that POST variables are available if relevant
	if (get_call_method() === 'POST') {
		include_post_data();
	}

	// check API authentication if required
	if ($API_METHODS[$method]["require_api_auth"] == true) {
		if (pam_authenticate(null, "api") == false) {
			throw new APIException(elgg_echo('APIException:APIAuthenticationFailed'));
		}
	}

	// check user authentication if required
	if ($API_METHODS[$method]["require_user_auth"] == true) {
		if (pam_authenticate() == false) {
			throw new APIException(elgg_echo('APIException:UserAuthenticationFailed'));
		}
	}

	return true;
}

/**
 * Executes a method.
 * A method is a function which you have previously exposed using expose_function.
 *
 * @param string $method Method, e.g. "foo.bar"
 * @return GenericResult The result of the execution.
 * @throws APIException, CallException
 */
function execute_method($method) {
	global $API_METHODS, $CONFIG;

	// method must be exposed
	if (!isset($API_METHODS[$method])) {
		throw new APIException(sprintf(elgg_echo('APIException:MethodCallNotImplemented'), $method));
	}

	// function must be callable
	if (!(isset($API_METHODS[$method]["function"])) || !(is_callable($API_METHODS[$method]["function"]))) {
		throw new APIException(sprintf(elgg_echo('APIException:FunctionDoesNotExist'), $method));
	}

	// check http call method
	if (strcmp(get_call_method(), $API_METHODS[$method]["call_method"]) != 0) {
		throw new CallException(sprintf(elgg_echo('CallException:InvalidCallMethod'), $method, $API_METHODS[$method]["call_method"]));
	}

	$parameters = get_parameters_for_method($method);

	if (verify_parameters($method, $parameters) == false) {
		// if verify_parameters fails, it throws exception which is not caught here
	}

	$serialised_parameters = serialise_parameters($method, $parameters);

	// Execute function: Construct function and calling parameters
	$function = $API_METHODS[$method]["function"];
	$serialised_parameters = trim($serialised_parameters, ", ");

	$result = eval("return $function($serialised_parameters);");

	// Sanity check result
	// If this function returns an api result itself, just return it
	if ($result instanceof GenericResult) {
		return $result;
	}

	if ($result === false) {
		throw new APIException(sprintf(elgg_echo('APIException:FunctionParseError'), $function, $serialised_parameters));
	}

	if ($result ===  NULL) {
		// If no value
		throw new APIException(sprintf(elgg_echo('APIException:FunctionNoReturn'), $function, $serialised_parameters));
	}

	// Otherwise assume that the call was successful and return it as a success object.
	return SuccessResult::getInstance($result);
}

/**
 * Get the request method.
 * @return string HTTP request method
 */
function get_call_method() {
	return $_SERVER['REQUEST_METHOD'];
}

/**
 * This function analyses all expected parameters for a given method
 *
 * This function sanitizes the input parameters and returns them in
 * an associated array.
 *
 * @param string $method The method
 * @return array containing parameters as key => value
 */
function get_parameters_for_method($method) {
	global $API_METHODS;

	$sanitised = array();

	// if there are parameters, sanitize them
	if (isset($API_METHODS[$method]['parameters'])) {
		foreach ($API_METHODS[$method]['parameters'] as $k => $v) {
			$param = get_input($k); // Make things go through the sanitiser
			if ($param !== '') {
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
 * @return POST data from PHP
 */
function get_post_data() {
	global $GLOBALS;

	$postdata = '';
	if (isset($GLOBALS['HTTP_RAW_POST_DATA']))
		$postdata = $GLOBALS['HTTP_RAW_POST_DATA'];

	// Attempt another method to return post data (incase always_populate_raw_post_data is switched off)
	if (!$postdata) {
		$postdata = file_get_contents('php://input');
	}

	return $postdata;
}

/**
 * This fixes the post parameters that are munged due to page handler
 */
function include_post_data() {

	$postdata = get_post_data();

	if (isset($postdata)) {
		$query_arr = elgg_parse_str($postdata);
		if (is_array($query_arr)) {
			foreach($query_arr as $name => $val) {
				set_input($name, $val);
			}
		}
	}
}

/**
 * Verify that the required parameters are present
 * @param $method
 * @param $parameters
 * @return true on success or exception
 * @throws APIException
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
			throw new APIException(sprintf(elgg_echo('APIException:InvalidParameter'), $key, $method));
		}

		// Check that the variable is present in the request if required
		if ($value['required'] && !array_key_exists($key, $parameters)) {
			throw new APIException(sprintf(elgg_echo('APIException:MissingParameterInMethod'), $key, $method));
		}
	}

	return true;
}

/**
 * Serialize an array of parameters for an API method call
 *
 * @param string $method API method name
 * @param array $parameters Array of parameters
 * @return string or exception
 * @throws APIException
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
			continue;
		}

		// Set variables casting to type.
		switch (strtolower($value['type']))
		{
			case 'int':
			case 'integer' :
				$serialised_parameters .= "," . (int)trim($parameters[$key]);
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
				$serialised_parameters .= ",'" . addcslashes(trim($parameters[$key]), "'") . "'";
				break;
			case 'float':
				$serialised_parameters .= "," . (float)trim($parameters[$key]);
				break;
			case 'array':
				// we can handle an array of strings, maybe ints, definitely not booleans or other arrays
				if (!is_array($parameters[$key])) {
					throw new APIException(sprintf(elgg_echo('APIException:ParameterNotArray'), $key));
				}

				$array = "array(";

				foreach ($parameters[$key] as $k => $v) {
					$k = sanitise_string($k);
					$v = sanitise_string($v);

					$array .= "'$k'=>'$v',";
				}

				$array = trim($array,",");

				$array .= ")";
				$array = ",$array";

				$serialised_parameters .= $array;
				break;
			default:
				throw new APIException(sprintf(elgg_echo('APIException:UnrecognisedTypeCast'), $value['type'], $key, $method));
		}
	}

	return $serialised_parameters;
}

// API authorization handlers /////////////////////////////////////////////////////////////////////

/**
 * PAM: Confirm that the call includes a valid API key
 * @return true if good API key - otherwise throws exception
 * @throws APIException
 */
function api_auth_key() {
	global $CONFIG;

	// check that an API key is present
	$api_key = get_input('api_key');
	if ($api_key == "") {
		throw new APIException(elgg_echo('APIException:MissingAPIKey'));
	}

	// check that it is active
	$api_user = get_api_user($CONFIG->site_id, $api_key);
	if (!$api_user) {
		// key is not active or does not exist
		throw new APIException(elgg_echo('APIException:BadAPIKey'));
	}

	// can be used for keeping stats
	// plugin can also return false to fail this authentication method
	return trigger_plugin_hook('api_key', 'use', $api_key, true);
}


/**
 * PAM: Confirm the HMAC signature
 * @return true if success - otherwise throws exception
 * @throws SecurityException
 */
function api_auth_hmac() {
	global $CONFIG;

	// Get api header
	$api_header = get_and_validate_api_headers();

	// Pull API user details
	$api_user = get_api_user($CONFIG->site_id, $api_header->api_key);

	if (!$api_user) {
		throw new SecurityException(elgg_echo('SecurityException:InvalidAPIKey'), ErrorResult::$RESULT_FAIL_APIKEY_INVALID);
	}

	// Get the secret key
	$secret_key = $api_user->secret;

	// get the query string
	$query = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '?') + 1);

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
	if ($api_header->method=="POST") {
		$postdata = get_post_data();
		$calculated_posthash = calculate_posthash($postdata, $api_header->posthash_algo);

		if (strcmp($api_header->posthash, $calculated_posthash)!=0) {
			throw new SecurityException(sprintf(elgg_echo('SecurityException:InvalidPostHash'), $calculated_posthash, $api_header->posthash));
		}
	}

	return true;
}

// HMAC /////////////////////////////////////////////////////////////////////

/**
 * This function looks at the super-global variable $_SERVER and extracts the various
 * header variables needed for the HMAC PAM
 *
 * @return stdClass Containing all the values.
 * @throws APIException Detailing any error.
 */
function get_and_validate_api_headers() {
	$result = new stdClass;

	$result->method = get_call_method();
	// Only allow these methods
	if (($result->method != "GET") && ($result->method != "POST")) {
		throw new APIException(elgg_echo('APIException:NotGetOrPost'));
	}

	$result->api_key = $_SERVER['HTTP_X_ELGG_APIKEY'];
	if ($result->api_key == "") {
		throw new APIException(elgg_echo('APIException:MissingAPIKey'));
	}

	$result->hmac = $_SERVER['HTTP_X_ELGG_HMAC'];
	if ($result->hmac == "") {
		throw new APIException(elgg_echo('APIException:MissingHmac'));
	}

	$result->hmac_algo = $_SERVER['HTTP_X_ELGG_HMAC_ALGO'];
	if ($result->hmac_algo == "") {
		throw new APIException(elgg_echo('APIException:MissingHmacAlgo'));
	}

	$result->time = $_SERVER['HTTP_X_ELGG_TIME'];
	if ($result->time == "") {
		throw new APIException(elgg_echo('APIException:MissingTime'));
	}

	// Must have been sent within 25 hour period.
	// 25 hours is more than enough to handle server clock drift.
	// This values determines how long the HMAC cache needs to store previous
	// signatures. Heavy use of HMAC is better handled with a shorter sig lifetime.
	// See cache_hmac_check_replay()
	if (($result->time<(time()-90000)) || ($result->time>(time()+90000))) {
		throw new APIException(elgg_echo('APIException:TemporalDrift'));
	}

	$result->nonce = $_SERVER['HTTP_X_ELGG_NONCE'];
	if ($result->nonce == "") {
		throw new APIException(elgg_echo('APIException:MissingNonce'));
	}

	if ($result->method == "POST") {
		$result->posthash = $_SERVER['HTTP_X_ELGG_POSTHASH'];
		if ($result->posthash == "") {
			throw new APIException(elgg_echo('APIException:MissingPOSTHash'));
		}

		$result->posthash_algo = $_SERVER['HTTP_X_ELGG_POSTHASH_ALGO'];
		if ($result->posthash_algo == "") {
			throw new APIException(elgg_echo('APIException:MissingPOSTAlgo'));
		}

		$result->content_type = $_SERVER['CONTENT_TYPE'];
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
 * @return string The php algorithm
 * @throws APIException if an algorithm is not supported.
 */
function map_api_hash($algo) {
	$algo = strtolower(sanitise_string($algo));
	$supported_algos = array(
		"md5" => "md5",	// TODO: Consider phasing this out
		"sha" => "sha1", // alias for sha1
		"sha1" => "sha1",
		"sha256" => "sha256"
	);

	if (array_key_exists($algo, $supported_algos)) {
		return $supported_algos[$algo];
	}

	throw new APIException(sprintf(elgg_echo('APIException:AlgorithmNotSupported'), $algo));
}

/**
 * Calculate the HMAC for the http request.
 * This function signs an api request using the information provided. The signature returned
 * has been base64 encoded and then url encoded.
 *
 * @param string $algo The HMAC algorithm used
 * @param string $time String representation of unix time
 * @param string $api_key Your api key
 * @param string $secret Your private key
 * @param string $get_variables URLEncoded string representation of the get variable parameters, eg "method=user&guid=2"
 * @param string $post_hash Optional sha1 hash of the post data.
 * @return string The HMAC signature
 */
function calculate_hmac($algo, $time, $nonce, $api_key, $secret_key, $get_variables, $post_hash = "") {
	global $CONFIG;

	elgg_log("HMAC Parts: $algo, $time, $api_key, $secret_key, $get_variables, $post_hash");

	$ctx = hash_init(map_api_hash($algo), HASH_HMAC, $secret_key);

	hash_update($ctx, trim($time));
	hash_update($ctx, trim($nonce));
	hash_update($ctx, trim($api_key));
	hash_update($ctx, trim($get_variables));
	if (trim($post_hash)!="") {
		hash_update($ctx, trim($post_hash));
	}

	return urlencode(base64_encode(hash_final($ctx, true)));
}

/**
 * Calculate a hash for some post data.
 *
 * TODO: Work out how to handle really large bits of data.
 *
 * @param string $postdata string The post data.
 * @param string $algo The algorithm used.
 * @return string The hash.
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
 * @return bool True if replay detected, false if not.
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

// API key functions /////////////////////////////////////////////////////////////////////

/**
 * Generate a new API user for a site, returning a new keypair on success.
 *
 * @param int $site_guid The GUID of the site. (default is current site)
 * @return stdClass object or false
 */
function create_api_user($site_guid) {
	global $CONFIG;

	if (!isset($site_guid)) {
		$site_guid = $CONFIG->site_id;
	}

	$site_guid = (int)$site_guid;

	$public = sha1(rand().$site_guid.microtime());
	$secret = sha1(rand().$site_guid.microtime().$public);

	$insert = insert_data("INSERT into {$CONFIG->dbprefix}api_users
		(site_guid, api_key, secret) values
		($site_guid, '$public', '$secret')");

	if ($insert) {
		return get_api_user($site_guid, $public);
	}

	return false;
}

/**
 * Find an API User's details based on the provided public api key. These users are not users in the traditional sense.
 *
 * @param int $site_guid The GUID of the site.
 * @param string $api_key The API Key
 * @return mixed stdClass representing the database row or false.
 */
function get_api_user($site_guid, $api_key) {
	global $CONFIG;

	$api_key = sanitise_string($api_key);
	$site_guid = (int)$site_guid;

	return get_data_row("SELECT * from {$CONFIG->dbprefix}api_users where api_key='$api_key' and site_guid=$site_guid and active=1");
}

/**
 * Revoke an api user key.
 *
 * @param int $site_guid The GUID of the site.
 * @param string $api_key The API Key (public).
 * @return bool
 */
function remove_api_user($site_guid, $api_key) {
	global $CONFIG;

	$keypair = get_api_user($site_guid, $api_key);
	if ($keypair) {
		return delete_data("DELETE from {$CONFIG->dbprefix}api_users where id={$keypair->id}");
	}

	return false;
}


// User Authorization functions ////////////////////////////////////////////////////////////////

/**
 * Check the user token
 * This examines whether an authentication token is present and returns true if
 * it is present and is valid. The user gets logged in so with the current
 * session code of Elgg, that user will be logged out of all other sessions.
 *
 * @param array/mixed $credentials
 * @return bool
 */
function pam_auth_usertoken($credentials = NULL) {
	global $CONFIG;

	$token = get_input('auth_token');
	if (!$token) {
		return false;
	}

	$validated_userid = validate_user_token($token, $CONFIG->site_id);

	if ($validated_userid) {
		$u = get_entity($validated_userid);

		// Could we get the user?
		if (!$u) {
			return false;
		}

		// Not an elgg user
		if ( (!$u instanceof ElggUser)) {
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
 * @return bool
 */
function pam_auth_session($credentials = NULL) {
	return isloggedin();
}

// user token functions /////////////////////////////////////////////////////////////////////

/**
 * Obtain a token for a user.
 *
 * @param string $username The username
 * @param int $expire minutes until token expires (default is 60 minutes)
 * @return bool
 */
function create_user_token($username, $expire = 60) {
	global $CONFIG;

	$site_guid = $CONFIG->site_id;
	$user = get_user_by_username($username);
	$time = time();
	$time += 60 * $expire;
	$token = md5(rand(). microtime() . $username . $time . $site_guid);

	if (!$user) {
		return false;
	}

	if (insert_data("INSERT into {$CONFIG->dbprefix}users_apisessions
				(user_guid, site_guid, token, expires) values
				({$user->guid}, $site_guid, '$token', '$time') on duplicate key update token='$token', expires='$time'")) {
		return $token;
	}

	return false;
}

/**
 * Get all tokens attached to a user
 *
 * @param int $user_guid The user GUID
 * @param int $site_guid The ID of the site (default is current site)
 * @return false if none available or array of stdClass objects
 * 		(see users_apisessions schema for available variables in objects)
 */
function get_user_tokens($user_guid, $site_guid) {
	global $CONFIG;

	if (!isset($site_guid)) {
		$site_guid = $CONFIG->site_id;
	}

	$site_guid = (int)$site_guid;
	$user_guid = (int)$user_guid;

	$tokens = get_data("SELECT * from {$CONFIG->dbprefix}users_apisessions
		where user_guid=$user_guid and site_guid=$site_guid");

	return $tokens;
}

/**
 * Validate a token against a given site.
 *
 * A token registered with one site can not be used from a different apikey(site), so be aware of this
 * during development.
 *
 * @param string $token The Token.
 * @param int $site_guid The ID of the site (default is current site)
 * @return mixed The user id attached to the token if not expired or false.
 */
function validate_user_token($token, $site_guid) {
	global $CONFIG;

	if (!isset($site_guid)) {
		$site_guid = $CONFIG->site_id;
	}

	$site_guid = (int)$site_guid;
	$token = sanitise_string($token);

	$time = time();

	$user = get_data_row("SELECT * from {$CONFIG->dbprefix}users_apisessions
		where token='$token' and site_guid=$site_guid and $time < expires");

	if ($user) {
		return $user->user_guid;
	}

	return false;
}

/**
 * Remove user token
 *
 * @param string $token
 * @param int $site_guid The ID of the site (default is current site)
 * @return bool
 */
function remove_user_token($token, $site_guid) {
	global $CONFIG;

	if (!isset($site_guid)) {
		$site_guid = $CONFIG->site_id;
	}

	$site_guid = (int)$site_guid;
	$token = sanitise_string($token);

	return delete_data("DELETE from {$CONFIG->dbprefix}users_apisessions
		where site_guid=$site_guid and token='$token'");
}

/**
 * Remove expired tokens
 *
 * @return bool
 */
function remove_expired_user_tokens() {
	global $CONFIG;

	$site_guid = $CONFIG->site_id;

	$time = time();

	return delete_data("DELETE from {$CONFIG->dbprefix}users_apisessions
		where site_guid=$site_guid and expires < $time");
}

// Client api functions ///////////////////////////////////////////////////////////////////

/**
 * Utility function to serialise a header array into its text representation.
 *
 * @param array $headers The array of headers "key" => "value"
 * @return string
 */
function serialise_api_headers(array $headers) {
	$headers_str = "";

	foreach ($headers as $k => $v) {
		$headers_str .= trim($k) . ": " . trim($v) . "\r\n";
	}

	return trim($headers_str);
}

/**
 * Send a raw API call to an elgg api endpoint.
 *
 * @param array $keys The api keys.
 * @param string $url URL of the endpoint.
 * @param array $call Associated array of "variable" => "value"
 * @param string $method GET or POST
 * @param string $post_data The post data
 * @param string $content_type The content type
 * @return string
 */
function send_api_call(array $keys, $url, array $call, $method = 'GET', $post_data = '', $content_type = 'application/octet-stream') {
	global $CONFIG;

	$headers = array();
	$encoded_params = array();

	$method = strtoupper($method);
	switch (strtoupper($method)) {
		case 'GET' :
		case 'POST' :
			break;
		default:
			$msg = sprintf(elgg_echo('NotImplementedException:CallMethodNotImplemented'), $method);
			throw new NotImplementedException($msg);
	}

	// Time
	$time = time();

	// Nonce
	$nonce = uniqid('');

	// URL encode all the parameters
	foreach ($call as $k => $v){
		$encoded_params[] = urlencode($k).'='.urlencode($v);
	}

	$params = implode('&', $encoded_params);

	// Put together the query string
	$url = $url . "?" . $params;

	// Construct headers
	$posthash = "";
	if ($method == 'POST') {
		$posthash = calculate_posthash($post_data, 'md5');
	}

	if ((isset($keys['public'])) && (isset($keys['private']))) {
		$headers['X-Elgg-apikey'] = $keys['public'];
		$headers['X-Elgg-time'] = $time;
		$headers['X-Elgg-nonce'] = $nonce;
		$headers['X-Elgg-hmac-algo'] = 'sha1';
		$headers['X-Elgg-hmac'] = calculate_hmac('sha1',
			$time,
			$nonce,
			$keys['public'],
			$keys['private'],
			$params,
			$posthash
		);
	}
	if ($method == 'POST') {
		$headers['X-Elgg-posthash'] = $posthash;
		$headers['X-Elgg-posthash-algo'] = 'md5';

		$headers['Content-type'] = $content_type;
		$headers['Content-Length'] = strlen($post_data);
	}

	// Opt array
	$http_opts = array(
		'method' => $method,
		'header' => serialise_api_headers($headers)
	);
	if ($method == 'POST') {
		$http_opts['content'] = $post_data;
	}

	$opts = array('http' => $http_opts);

	// Send context
	$context = stream_context_create($opts);

	// Send the query and get the result and decode.
	elgg_log("APICALL: $url");
	$results = file_get_contents($url, false, $context);

	return $results;
}

/**
 * Send a GET call
 *
 * @param string $url URL of the endpoint.
 * @param array $call Associated array of "variable" => "value"
 * @param array $keys The keys dependant on chosen authentication method
 * @return string
 */
function send_api_get_call($url, array $call, array $keys) {
	return send_api_call($keys, $url, $call);
}

/**
 * Send a GET call
 *
 * @param string $url URL of the endpoint.
 * @param array $call Associated array of "variable" => "value"
 * @param array $keys The keys dependant on chosen authentication method
 * @param string $post_data The post data
 * @param string $content_type The content type
 * @return string
 */
function send_api_post_call($url, array $call, array $keys, $post_data, $content_type = 'application/octet-stream') {
	return send_api_call($keys, $url, $call, 'POST', $post_data, $content_type);
}

/**
 * Return a key array suitable for the API client using the standard authentication method based on api-keys and secret keys.
 *
 * @param string $secret_key Your secret key
 * @param string $api_key Your api key
 * @return array
 */
function get_standard_api_key_array($secret_key, $api_key) {
	return array('public' => $api_key, 'private' => $secret_key);
}

// System functions ///////////////////////////////////////////////////////////////////////

/**
 * Simple api to return a list of all api's installed on the system.
 * @return array
 */
function list_all_apis() {
	global $API_METHODS;

	// sort first
	ksort($API_METHODS);

	return $API_METHODS;
}

/**
 * The auth.gettoken API.
 * This API call lets a user log in, returning an authentication token which can be used
 * to authenticate a user for a period of time. It is passed in future calls as the parameter
 * auth_token.
 *
 * @param string $username Username
 * @param string $password Clear text password
 * @return string Token string or exception
 * @throws SecurityException
 */
function auth_gettoken($username, $password) {
	if (authenticate($username, $password)) {
		$token = create_user_token($username);
		if ($token) {
			return $token;
		}
	}

	throw new SecurityException(elgg_echo('SecurityException:authenticationfailed'));
}

// Error handler functions ////////////////////////////////////////////////////////////////

/** Define a global array of errors */
$ERRORS = array();

/**
 * API PHP Error handler function.
 * This function acts as a wrapper to catch and report PHP error messages.
 *
 * @see http://uk3.php.net/set-error-handler
 * @param int $errno
 * @param string $errmsg
 * @param string $filename
 * @param int $linenum
 * @param array $vars
 * @return none
 */
function __php_api_error_handler($errno, $errmsg, $filename, $linenum, $vars) {
	global $ERRORS;

	$error = date("Y-m-d H:i:s (T)") . ": \"" . $errmsg . "\" in file " . $filename . " (line " . $linenum . ")";

	switch ($errno) {
		case E_USER_ERROR:
			error_log("ERROR: " . $error);
			$ERRORS[] = "ERROR: " .$error;

			// Since this is a fatal error, we want to stop any further execution but do so gracefully.
			throw new Exception("ERROR: " . $error);
			break;

		case E_WARNING :
		case E_USER_WARNING :
			error_log("WARNING: " . $error);
			$ERRORS[] = "WARNING: " .$error;
			break;

		default:
			error_log("DEBUG: " . $error);
			$ERRORS[] = "DEBUG: " .$error;
	}
}

/**
 * API PHP Exception handler.
 * This is a generic exception handler for PHP exceptions. This will catch any
 * uncaught exception, end API execution and return the result to the requestor
 * as an ErrorResult in the requested format.
 *
 * @param Exception $exception
 * @return none
 */
function __php_api_exception_handler($exception) {

	error_log("*** FATAL EXCEPTION (API) *** : " . $exception);

	$code   = $exception->getCode() == 0 ? ErrorResult::$RESULT_FAIL : $exception->getCode();
	$result = new ErrorResult($exception->getMessage(), $code, NULL);

	page_draw($exception->getMessage(), elgg_view("api/output", array("result" => $result)));
}


// Services handler ///////////////////////////////////////////

/**
 * Services handler - turns request over to the registered handler
 * If no handler is found, this returns a 404 error
 *
 * @param string $handler
 * @param array $request
 */
function service_handler($handler, $request) {
	global $CONFIG;

	// setup the input parameters since this comes through rewrite rule
	$query = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '?')+1);
	if (isset($query)) {
		$query_arr = elgg_parse_str($query);
		if (is_array($query_arr)) {
			foreach($query_arr as $name => $val) {
				set_input($name, $val);
			}
		}
	}

	set_context('api');

	$request = explode('/',$request);

	// after the handler, the first identifier is response format
	// ex) http://example.org/services/api/rest/xml/?method=test
	$reponse_format = array_shift($request);
	// Which view - xml, json, ...
	if ($reponse_format) {
		elgg_set_viewtype($reponse_format);
	} else {
		// default to xml
		elgg_set_viewtype("xml");
	}

	if (!isset($CONFIG->servicehandler) || empty($handler)) {
		// no handlers set or bad url
		header("HTTP/1.0 404 Not Found");
		exit;
	} else if (isset($CONFIG->servicehandler[$handler]) && is_callable($CONFIG->servicehandler[$handler])) {
		$function = $CONFIG->servicehandler[$handler];
		$function($request, $handler);
	} else {
		// no handler for this web service
		header("HTTP/1.0 404 Not Found");
		exit;
	}
}

/**
 * Registers a web services handler
 *
 * @param string $handler web services type
 * @param string $function Your function name
 * @return true|false Depending on success
 */
function register_service_handler($handler, $function) {
	global $CONFIG;
	if (!isset($CONFIG->servicehandler)) {
		$CONFIG->servicehandler = array();
	}
	if (is_callable($function)) {
		$CONFIG->servicehandler[$handler] = $function;
		return true;
	}

	return false;
}

/**
 * Remove a web service
 * To replace a web service handler, register the desired handler over the old on
 * with register_service_handler().
 *
 * @param string $handler web services type
 */
function unregister_service_handler($handler) {
	global $CONFIG;
	if (isset($CONFIG->servicehandler) && isset($CONFIG->servicehandler[$handler])) {
		unset($CONFIG->servicehandler[$handler]);
	}
}

// REST handler //////////////////////////////////////////////////////////////

/**
 * REST API handler
 */
function rest_handler() {
	global $CONFIG;

	require $CONFIG->path . "services/api/rest_api.php";
}

// Initialisation /////////////////////////////////////////////////////////////

/**
 * Unit tests for API
 */
function api_unit_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = $CONFIG->path . 'engine/tests/services/api.php';
	return $value;
}

/**
 * Initialise the API subsystem.
 *
 */
function api_init() {
	// Register a page handler, so we can have nice URLs
	register_service_handler('rest','rest_handler');

	register_plugin_hook('unit_test', 'system', 'api_unit_test');

	// expose the list of api methods
	expose_function("system.api.list", "list_all_apis", NULL, elgg_echo("system.api.list"), "GET", false, false);

	// The authentication token api
	expose_function("auth.gettoken",
					"auth_gettoken", array(
											'username' => array ('type' => 'string'),
											'password' => array ('type' => 'string'),
											),
					elgg_echo('auth.gettoken'),
					'POST',
					false,
					false);
}


register_elgg_event_handler('init','system','api_init');
