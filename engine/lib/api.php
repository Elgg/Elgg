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