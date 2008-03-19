<?php
	/**
	 * Elgg API
	 * Functions and objects which make up the API engine.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */
	
	// Result classes /////////////////////////////////////////////////////////////////////////

	/**
	 * @class GenericResult Result superclass.
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 */
	abstract class GenericResult
	{	
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
		protected function setStatusCode($status, $message = "")
		{
			$this->status_code = $status;
			$this->message = $message;
		}
		
		/**
		 * Set the result.
		 *
		 * @param mixed $result
		 */
		protected function setResult($result) { $this->result = $result; }
		
		protected function getStatusCode() { return $this->status_code; }
		protected function getStatusMessage() { return $this->message; }
		protected function getResult() { return $this->result; }

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
		public function toStdClass()
		{
			global $ERRORS, $CONFIG, $PAM_HANDLER_MSG;
			
			$result = new stdClass;
			
			$result->status = $this->getStatusCode();
			if ($this->getStatusMessage()!="") $result->message = $this->getStatusMessage();
			
			$resultdata = $this->getResult(); 
			if (isset($resultdata)) $result->result = $resultdata;

			if ((isset($CONFIG->debug)) && ($CONFIG->debug == true))
			{
				if (count($ERRORS))
					$result->runtime_errors = $ERRORS;
					
				if (count($PAM_HANDLER_MSG))
					$result->pam = $PAM_HANDLER_MSG;
			}

			return $result;
		}
	}
	
	/**
	 * @class SuccessResult
	 * Generic success result class, extend if you want to do something special.
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 */
	class SuccessResult extends GenericResult
	{
		public static $RESULT_SUCCESS 	= 0;  // Do not change this from 0

		public function SuccessResult($result)
		{	
			$this->setResult($result);
			$this->setStatusCode(SuccessResult::$RESULT_SUCCESS);
		}
		
		public static function getInstance($result)
		{	
			// Return a new error object.
			return new SuccessResult($result);
		}
	}
	
	/**
	 * @class ErrorResult
	 * The error result class.
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 */
	class ErrorResult extends GenericResult
	{
		public static $RESULT_FAIL 		= -1 ; // Fail with no specific code

		public static $RESULT_FAIL_APIKEY_DISABLED = -30;
		public static $RESULT_FAIL_APIKEY_INACTIVE = -31;
		public static $RESULT_FAIL_APIKEY_INVALID = -32;
		
		public static $RESULT_FAIL_AUTHTOKEN = -20; // Invalid, expired or missing auth token

		public function ErrorResult($message, $code = "", Exception $exception = NULL)
		{
			if ($code == "")
				$code = GenericResult::$RESULT_FAIL;
				
			if ($exception!=NULL)
				$this->setResult($exception->__toString());
				
			$this->setStatusCode($code, $message);
		}
				
		/**
		 * Get a new instance of the ErrorResult.
		 *
		 * @param string $message
		 * @param int $code
		 * @param Exception $exception Optional exception for generating a stack trace.
		 */
		public static function getInstance($message, $code = "", Exception $exception = NULL)
		{	
			// Return a new error object.
			return new ErrorResult($message, $code, $exception);
		}
	}
	
	// PAM AUTH HMAC functions ////////////////////////////////////////////////////////////////
	
	/**
	 * Map various algorithms to their PHP equivs.
	 * This also gives us an easy way to disable algorithms.
	 * 
	 * @param string $algo The algorithm
	 * @return string The php algorithm
	 * @throws APIException if an algorithm is not supported. 
	 */
	function map_api_hash($algo)
	{
		$algo = strtolower(sanitise_string($algo));
		$supported_algos = array(
			"md5" => "md5",
			"sha" => "sha1", // alias for sha1
			"sha1" => "sha1",
			"sha256" => "sha256"
		);
		
		if (array_key_exists($algo))
			return $supported_algos[$algo];
			
		throw new APIException("Algorithm '$algo' is not supported or has been disabled.");
	}
	
	/**
	 * Calculate the HMAC for the query.
	 * This function signs an api request using the information provided and is then verified by
	 * searunner.
	 * 
	 * @param $algo string The HMAC algorithm used as stored in X-Searunner-hmac-algo.
	 * @param $time string String representation of unix time as stored in X-Searunner-time.
	 * @param $api_key string Your api key.
	 * @param $secret string Your secret key.
	 * @param $get_variables string URLEncoded string representation of the get variable parameters, eg "format=php&method=searunner.test".
	 * @param $post_hash string Optional sha1 hash of the post data.
	 * @return string The HMAC string.
	 */
	function calculate_hmac($algo, $time, $api_key, $secret_key, $get_variables, $post_hash = "")
	{
		$ctx = hash_init(map_api_hash($algo), HASH_HMAC, $secret_key);

		hash_update($ctx, trim($time));
		hash_update($ctx, trim($api_key));
		hash_update($ctx, trim($get_variables));
		if (trim($post_hash)!="") hash_update($ctx, trim($post_hash));

		return hash_final($ctx);
	}

	/**
	 * Calculate a hash for some post data.
	 * 
	 * TODO: Work out how to handle really large bits of data.
	 *
	 * @param $postdata string The post data.
	 * @param $algo string The algorithm used.
	 * @return string The hash.
	 */
	function calculate_posthash($postdata, $algo)
	{
		$ctx = hash_init(map_api_hash($algo));

		hash_update($ctx, $postdata);

		return hash_final($ctx);
	}
	
	/**
	 * This function will do two things. Firstly it verifys that a $hmac hasn't been seen before, and 
	 * secondly it will add the given hmac to the cache.
	 * 
	 * TODO : REWRITE TO NOT USE ZEND
	 * 
	 * @param $hmac The hmac string.
	 * @return bool True if replay detected, false if not.
	 */
	function cache_hmac_check_replay($hmac)
	{
		global $CONFIG;

		throw new NotImplementedException("Writeme!");
		
		return true;
	}
	
	/**
	 * Find an API User's details based on the provided public api key.
	 *
	 * @param int $site_guid The GUID of the site.
	 * @param string $api_key The API Key
	 * @return mixed stdClass representing the database row or false.
	 */
	function get_api_user($site_guid, $api_key)
	{
		global $CONFIG;
		
		$api_key = sanitise_string($api_key);
		$site_guid = (int)$site_guid;
		
		return get_data_row("SELECT * from {$CONFIG->dbprefix}api_users where api_key='$api_key' and site_guid=$site_guid and active=1");	
	}
	
	/**
	 * This function looks at the super-global variable $_SERVER and extracts the various
	 * header variables needed to pass to the validation functions after performing basic validation.
	 *
	 * @return stdClass Containing all the values.
	 * @throws APIException Detailing any error.
	 */
	function get_and_validate_api_headers()
	{
		$result = new stdClass;
		
		$result->method = $_SERVER['REQUEST_METHOD'];
		if (($result->method != "GET") && ($result->method!= "POST")) // Only allow these methods
			throw new APIException("Request method must be GET or POST");

		$result->api_key = $_SERVER['HTTP_X_ELGG_APIKEY'];
		if ($result->api_key == "")
			throw new APIException("Missing X-Elgg-apikey HTTP header");
		
		$result->hmac = $_SERVER['HTTP_X_ELGG_HMAC'];
		if ($result->hmac == "")
			throw new APIException("Missing X-Elgg-hmac header");
		
		$result->hmac_algo = $_SERVER['HTTP_X_ELGG_HMAC_ALGO'];
		if ($result->hmac_algo == "")
			throw new APIException("Missing X-Elgg-hmac-algo header");
		
		$result->time = $_SERVER['HTTP_X_ELGG_TIME'];
		if ($result->time == "") 
			throw new APIException("Missing X-Elgg-time header"); 
		if (($result->time<(microtime(true)-86400.00)) || ($result->time>(microtime(true)+86400.00))) // Basic timecheck, think about making this smaller if we get loads of users and the cache gets really big.
			throw new APIException("X-Elgg-time is too far in the past or future");
		
		$result->get_variables = $_SERVER['QUERY_STRING'];
		if ($result->get_variables == "")
			throw new APIException("No data on the query string");

		if ($result->method=="POST")
		{
			$result->posthash = $_SERVER['HTTP_X_ELGG_POSTHASH'];
			if ($result->posthash == "") 
				throw new APIException("Missing X-Elgg-posthash header");
			
			$result->posthash_algo = $_SERVER['HTTP_X_ELGG_POSTHASH_ALGO'];
			if ($result->posthash_algo == "") 
				throw new APIException("Missing X-Elgg-posthash_algo header");
				
			$result->content_type = $_SERVER['CONTENT_TYPE'];
			if ($result->content_type == "")
				throw new APIException("Missing content type for post data");
		}
		
		return $result;
	}
	
	/**
	 * Return a sanitised form of the POST data sent to the script
	 *
	 * @return string
	 */
	function get_post_data()
	{
		global $GLOBALS;
		
		return $GLOBALS['HTTP_RAW_POST_DATA'];	
	}
	
	// PAM functions //////////////////////////////////////////////////////////////////////////
	
	$PAM_HANDLERS = array(); 
	$PAM_HANDLER_MSG = array(); // Messages
	
	/**
	 * Register a method of authenticating an incoming API request.
	 * This function registers a PAM handler which is a function that matches the desciption pam_handler_name() 
	 * and returns either 'true' if an incoming api request was authorised, false or throws an exception if not.
	 * 
	 * The handlers are tried in turn until one of them successfully authenticates the session.
	 * 
	 * This architecture lets an administrator choose what methods to accept for API authentication or 
	 *
	 * @param unknown_type $handler
	 */
	function register_api_pam_handler($handler)
	{
		global $PAM_HANDLERS;
		
		if (is_callable($handler))
		{
			$PAM_HANDLERS[$handler] = $handler;
			return true;
		}
		
		return false;
	}
	
	/**
	 * Magically authenticate an API session using one of the registered methods.
	 * 
	 * This function will return true if authentication was possible, otherwise it'll throw an exception.
	 * 
	 * If $CONFIG->debug is set then additional debug information will be returned.
	 */
	function api_pam_authenticate()
	{
		global $PAM_HANDLERS, $PAM_HANDLER_MSG;
		global $CONFIG;
		
		$dbg_msgs = array();

		foreach ($PAM_HANDLERS as $k => $v)
		{
			try {
				// Execute the handler 
				if ($v())
				{
					// Explicitly returned true
					$PAM_HANDLER_MSG[$k] = "Authenticated!";

					return true;
				}
				else
					$PAM_HANDLER_MSG[$k] = "Not Authenticated.";
			} 
			catch (Exception $e)
			{
				$PAM_HANDLER_MSG[$k] = "$e";
			}	
		}
		
		// Got this far, so no methods could be found to authenticate the session
		throw new SecurityException("No authentication methods were found that could authenticate the session.");
	}
	
	/**
	 * See if the user has a valid login sesson.
	 */
	function pam_auth_session()
	{
		return isloggedin();
	}
	
	/**
	 * Secure authentication through headers and HMAC.
	 */
	function pam_auth_hmac()
	{
		global $CONFIG;
		
		$api_header = get_and_validate_api_headers(); // Get api header
		$api_user = get_api_user($CONFIG->api_header->api_key); // Pull API user details
	
		if ($api_user)
		{
			// Get the secret key
			$secret_key = $api_user->secret;
		
			// Validate HMAC - TODO: Maybe find a simpler way to do this that is still secure...?
			$hmac = calculate_hmac($api_header->hmac_algo, 
					$api_header->time, 
					$api_header->api_key, 
					$secret_key, 
					$api_header->get_variables, 
					$api_header->method == 'POST' ? $api_header->posthash : "");
				
			if (strcmp(
				$api_header->hmac,
				$hmac	
			)==0)
			{
				// Now make sure this is not a replay
				if (!cache_hmac_check_replay($hmac)) 
				{
					// Validate post data
					if ($api_header->method=="POST")
					{
						$postdata = get_post_data();
						$calculated_posthash = calculate_posthash($postdata, $api_header->posthash_algo);

						if (strcmp($api_header->posthash, $calculated_posthash)!=0)
							throw new SecurityException("POST data hash is invalid - Expected $calculated_posthash but got {$api_header->posthash}.");
					}
					
					// If we've passed all the checks so far then we can be reasonably certain that the request is authentic, so return this fact to the PAM engine.
					return true;
				}
				else
					throw new SecurityException("Packet signature already seen.");
			}
			else 
				throw new SecurityException("HMAC is invalid.  {$api_header->hmac} != [calc]$hmac = {$api_header->hmac_algo}(**SECRET KEY**, time:{$api_header->time}, apikey:{$api_header->api_key}, get_vars:{$api_header->get_variables}" . ($api_header->method=="POST"? "posthash:$api_header->posthash}" : ")"));
		}
		else
			throw new SecurityException("Invalid or missing API Key.",ErrorResult::$RESULT_FAIL_APIKEY_INVALID);
			
		return false;
	}
	
	// XML functions //////////////////////////////////////////////////////////////////////////
	
	/**
	 * This function serialises an object recursively into an XML representation.
	 * @param $data object The object to serialise.
	 * @param $n int Level, only used for recursion.
	 * @return string The serialised XML output.
	 */
	function serialise_object_to_xml($data, $name = "", $n = 0)
	{
		$classname = ($name=="" ? get_class($data) : $name);
		
		$vars = get_object_vars($data);
		
		$output = "";
		
		if ($n==0) $output = "<$classname>";
		
		foreach ($vars as $key => $value)
		{
			$output .= "<$key type=\"".gettype($value)."\">";
			
			if (is_object($value))
				$output .= serialise_object_to_xml($value, $key, $n+1);
			else if (is_array($value))
				$output .= serialise_array_to_xml($value, $n+1);
			else
				$output .= htmlentities($value);
			
			$output .= "</$key>\n";
		}
		
		if ($n==0) $output .= "</$classname>\n";
		
		return $output;
	}

	/**
	 * Serialise an array.
	 *
	 * @param array $data
	 * @param int $n Used for recursion
	 * @return string
	 */
	function serialise_array_to_xml(array $data, $n = 0)
	{
		$output = "";
		
		if ($n==0) $output = "<array>\n";
		
		foreach ($data as $key => $value)
		{
			$item = "array_item";
			
			if (is_numeric($key))
				$output .= "<$item name=\"$key\" type=\"".gettype($value)."\">";
			else
			{
				$item = $key;
				$output .= "<$item type=\"".gettype($value)."\">";
			}
			
			if (is_object($value))
				$output .= serialise_object_to_xml($value, $item, $n+1);
			else if (is_array($value))
				$output .= serialise_array_to_xml($value, $n+1);
			else
				$output .= htmlentities($value);
			
			$output .= "</$item>\n";
		}
		
		if ($n==0) $output = "</array>\n";
		
		return $output;
	}
	
	// Output functions ///////////////////////////////////////////////////////////////////////
	
	$API_OUTPUT_FUNCTIONS = array();
	
	/**
	 * Register an API output handler.
	 * This function is used by the system and the plugins to register an output encoding method for 
	 * returning API results.
	 * 
	 * @param string $form The format string, eg 'xml' or 'php'
	 * @param string $function The function, which must be in the format function_name(stdClass $result) and return a string.
	 * @return bool
	 */
	function register_api_outputhandler($form, $function)
	{
		global $API_OUTPUT_FUNCTIONS;
		
		if ( ($form!="") && ($function!=""))
		{
			$API_OUTPUT_FUNCTIONS[$form] = $function;
			
			return true;
		}
		
		return false;
	}

	/**
	 * Output the result, with the given fault.
	 * 
	 * @param GenericResult $result Result object.
	 * @param string $format The format
	 * @return string
	 */
	function output_result(GenericResult $result, $format)
	{
		global $API_OUTPUT_FUNCTIONS;
	
		if (
			(array_key_exists($format, $API_OUTPUT_FUNCTIONS)) &&
			(is_callable($API_OUTPUT_FUNCTIONS[$format]))
		)
			return $API_OUTPUT_FUNCTIONS[$format]($result->toStdClass());
			
		// We got here, so no output format was found. Output an error
		$result = print_r($result, true);	
			
		return <<< END
<html>
<head><title>Something went wrong...</title></head>
<body>
	<h1>API Output Error</h1>	
	<p>Something went badly wrong while outputting the result of your request to '$format'. The result and any errors are displayed in 
	raw text below.</p>
	<pre>
$result
</pre>
</body>
</html>
END;
	}
	
	
	function xml_result_handler(stdClass $result)
	{
		header("Content-Type: text/xml");
		return serialise_object_to_xml($result, "elgg");
	}
	
	function php_result_handler(stdClass $result)
	{
		return serialize($result);
	}
	
	function json_result_handler(stdClass $result)
	{
		return json_encode($result);
	}
	
	function cvs_result_handler(stdClass $result)
	{
		throw new NotImplementedException("CVS View currently not implemented");
	}
	
	// Register some format handlers
	register_api_outputhandler('xml', 'xml_result_handler');
	register_api_outputhandler('php', 'php_result_handler');
	register_api_outputhandler('json', 'json_result_handler');
	register_api_outputhandler('cvs', 'cvs_result_handler');
	
	
	// Error handler functions ////////////////////////////////////////////////////////////////
	
	/** Define a global array of errors */
	$ERRORS = array();
	
	/**
	 * PHP Error handler function.
	 * This function acts as a wrapper to catch and report PHP error messages.
	 * 
	 * @see http://uk3.php.net/set-error-handler
	 * @param unknown_type $errno
	 * @param unknown_type $errmsg
	 * @param unknown_type $filename
	 * @param unknown_type $linenum
	 * @param unknown_type $vars
	 */
	function __php_api_error_handler($errno, $errmsg, $filename, $linenum, $vars)
	{
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
	 * PHP Exception handler.
	 * This is a generic exception handler for PHP exceptions. This will catch any 
	 * uncaught exception and return it as an ErrorResult in the requested format.
	 *
	 * @param Exception $exception
	 */
	function __php_api_exception_handler($exception) {
		
		error_log("*** FATAL EXCEPTION (API) *** : " . $exception);
			
		echo output_result(
			ErrorResult::getInstance(
				$exception->getMessage(), 
				$exception->getCode() == 0 ? ErrorResult::$RESULT_FAIL : $exception->getCode(), 
				$exception),
				
			get_input('format','php') // Attempt to get the requested format if passed.
		);
	}
	
?>