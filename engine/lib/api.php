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
		 * @return stdClass Object containing the serialised result.
		 */
		public function toStdClass()
		{
			global $ERRORS;
			
			$result = new stdClass;
			
			$result->status = $this->getStatusCode();
			if ($this->getStatusMessage()!="") $result->message = $this->getStatusMessage();
			
			$resultdata = $this->getResult(); 
			if (isset($resultdata)) $result->result = $resultdata;

			if (count($ERRORS))
				$result->runtime_errors = $ERRORS;

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
	
	// API functions //////////////////////////////////////////////////////////////////////////
	
	/** Create the environment for API Calls */
	$ApiEnvironment = new stdClass;

		
	/** 
	 * An array holding methods.
	 * The structure of this is 
	 * 	$METHODS = array (
	 * 		"api.method" => array (
	 * 			"function" = 'my_function_callback'
	 * 			"parameters" = array (
	 * 				"variable" = array ( // NB, the order is the same as defined by your function callback
	 * 					type => 'int' | 'bool' | 'float' | 'string'
	 * 					required => true (default) | false  
	 * 				)
	 * 			)
	 * 			"require_auth" => true (default) | false
	 * 		)
	 *  )
	 */
	$METHODS = array();
	
	
	// export function

	/**
	 * Expose an arbitrary function as an api call.
	 * 
	 * Limitations: Currently can not expose functions which expect objects or arrays.
	 * 
	 * @param string $method The api name to expose this as, eg "myapi.dosomething"
	 * @param string $function Your function callback.
	 * @param array $parameters Optional list of parameters in the same order as in your function, with optional parameters last.
	 * @param bool $require_auth Whether this requires a user authentication token or not (default is true)
	 * @return bool
	 */
	function expose_function($method, $function, array $parameters = NULL, $require_auth = true)
	{
	}

	/**
	 * Executes a method.
	 * A method is a function which you have previously exposed using expose_function.
	 *
	 * @param string $method Method, e.g. "foo.bar"
	 * @param array $parameters Array of parameters in the format "variable" => "value", thse will be sanitised before being fed to your handler.
	 * @param string $token The authentication token to authorise this method call.
	 * @return GenericResult The result of the execution.
	 * @throws APIException, SecurityException
	 */
	function execute_method($method, array $parameters, $token = "")
	{

		// TODO: If auth token, validate user and set session
		
		
		// Return an error if not found
		throw new APIException("Method call '$method' has not been implemented."); 
	}
	
	/**
	 * This function looks at the super-global variable $_SERVER and extracts the various
	 * header variables needed to pass to the validation functions after performing basic validation.
	 *
	 * @return stdClass Containing all the values.
	 * @throws APIException Detailing the error.
	 */
	function get_and_validate_api_headers()
	{
		$result = new stdClass;
		
		$result->method = trim($_SERVER['REQUEST_METHOD']);
		if (($result->method != "GET") && ($result->method!= "POST")) // Only allow these methods
			throw new APIException("Request method must be GET or POST");

		$result->api_key = trim($_SERVER['HTTP_X_ELGG_APIKEY']);
		if ($result->api_key == "")
			throw new APIException("Missing X-Elgg-apikey HTTP header");
		
		$result->hmac = trim($_SERVER['HTTP_X_ELGG_HMAC']);
		if ($result->hmac == "")
			throw new APIException("Missing X-Elgg-hmac header");
		
		$result->hmac_algo = trim($_SERVER['HTTP_X_ELGG_HMAC_ALGO']);
		if ($result->hmac_algo == "")
			throw new APIException("Missing X-Elgg-hmac-algo header");
		
		$result->time = trim($_SERVER['HTTP_X_ELGG_TIME']);
		if ($result->time == "") 
			throw new APIException("Missing X-Elgg-time header"); 
		if (($result->time<(microtime(true)-86400.00)) || ($result->time>(microtime(true)+86400.00))) // Basic timecheck, think about making this smaller if we get loads of users and the cache gets really big.
			throw new APIException("X-Elgg-time is too far in the past or future");
		
		$result->get_variables = trim($_SERVER['QUERY_STRING']);
		if ($result->get_variables == "")
			throw new APIException("No data on the query string");

		if ($result->method=="POST")
		{
			$result->posthash = trim($_SERVER['HTTP_X_ELGG_POSTHASH']);
			if ($result->posthash == "") 
				throw new APIException("Missing X-Elgg-posthash header");
			
			$result->posthash_algo = trim($_SERVER['HTTP_X_ELGG_POSTHASH_ALGO']);
			if ($result->posthash_algo == "") 
				throw new APIException("Missing X-Elgg-posthash_algo header");
				
			$result->content_type = trim($_SERVER['CONTENT_TYPE']);
			if ($result->content_type == "")
				throw new APIException("Missing content type for post data");
		}
		
		return $result;
	}
	
	/**
	 * Find an API User's details based on the provided public api key.
	 *
	 * @param string $api_key The API Key
	 * @return mixed stdClass representing the database row or false.
	 */
	function get_api_user($api_key)
	{
		global $CONFIG;
		
		$api_key = sanitise_string($api_key);
		
		return get_data_row("SELECT * from {$CONFIG->dbprefix}api_users where api_key='$api_key'");	
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
		$ctx = hash_init($algo, HASH_HMAC, $secret_key);

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
		$ctx = hash_init($algo);

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
	
	/**
	 * Get output for a result in one of a number of formats.
	 *
	 * @param GenericResult $result
	 * @param string $format Optional format, if not specified or invalid, PHP is assumed.
	 * @return mixed The serialised output, or false.
	 */
	function get_serialised_result(GenericResult $result, $format = "php")
	{
		$format = trim(strtolower($format));
		
		if ($result)
		{
			// Echo
			switch ($format)
			{
				case 'xml' : return serialise_object_to_xml($result->toStdClass(), "Elgg");
				
				case 'json' : return json_encode($result->toStdClass()); 

				case 'php' :
				default: return serialize($result->toStdClass());
			}
		}
		
		return false;
	}
	
	/**
	 * Output a result, altering headers and mime-types as necessary.
	 *
	 * @param GenericResult $result
	 * @param string $format Optional format, if not specified or invalid, PHP is assumed.
	 */
	function output_result(GenericResult $result, $format = 'php')
	{
		switch ($format)
		{
			case 'xml' : header('Content-Type: text/xml'); 
		}
		
		echo get_serialised_result($result, $format);
	}
	
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
			
		output_result(
			ErrorResult::getInstance(
				$exception->getMessage(), 
				$exception->getCode() == 0 ? ErrorResult::$RESULT_FAIL : $exception->getCode(), 
				$exception),
				
			get_input('format','php') // Attempt to get the requested format if passed.
		);
	}
	
?>