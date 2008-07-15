<?php
	/**
	 * Elgg XML-RPC library.
	 * Contains functions and classes to handle XML-RPC services, currently only server only. 
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// XMLRPC Call ////////////////////////////////////////////////////////////////////////////
	
	/**
	 * @class XMLRPCCall
	 * This class represents 
	 * @author Marcus Povey
	 */
	class XMLRPCCall
	{
		/** Method name */
		private $methodname;
		/** Parameters */
		private $params;
		
		/**
		 * Construct a new XML RPC Call
		 *
		 * @param string $xml
		 */
		function __construct($xml)
		{
			$this->parse($xml);
		}
		
		/**
		 * Return the method name associated with the call.
		 *
		 * @return string
		 */
		public function getMethodName() { return $this->methodname; }
		
		/**
		 * Return the parameters.
		 * Returns a nested array of XmlElement.
		 * 
		 * @see XmlElement 
		 * @return array
		 */
		public function getParameters() { return $this->params; }
		
		/**
		 * Parse the xml into its components according to spec. 
		 * This first version is a little primitive. 
		 *
		 * @param string $xml
		 */
		private function parse($xml)
		{
			$xml = xml_2_object($xml);
			
			// sanity check
			if ((isset($xml->name)) && (strcasecmp($xml->name, "methodCall")!=0))
				throw new CallException(elgg_echo('CallException:NotRPCCall'));
			
			// method name
			$this->methodname = $xml->children[0]->content;
			
			// parameters 
			$this->params = $xml->children[1]->children;
			
			print_r($this);
		}
	}
	
	// Response classes ///////////////////////////////////////////////////////////////////////

	/**
	 * @class XMLRPCParameter Superclass for all RPC parameters.
	 * @author Marcus Povey
	 */
	abstract class XMLRPCParameter
	{
		protected $value;

		function __construct() { }
			
	}
	
	/**
	 * @class XMLRPCIntParameter An Integer.
	 * @author Marcus Povey
	 */
	class XMLRPCIntParameter extends XMLRPCParameter
	{
		function __construct($value)
		{
			parent::__construct();
			
			$this->value = (int)$value; 
		}
		
		function __toString() 
		{
			return "<value><i4>{$this->value}</i4></value>\n";
		}
	}
	
	/**
	 * @class XMLRPCBoolParameter A boolean.
	 * @author Marcus Povey
	 */
	class XMLRPCBoolParameter extends XMLRPCParameter
	{
		function __construct($value)
		{
			parent::__construct();
			
			$this->value = (bool)$value; 
		}
		
		function __toString() 
		{
			$code = ($this->value) ? "1" : "0";
			return "<value><boolean>{$code}</boolean></value>\n";
		}
	}
	
	/**
	 * @class XMLRPCStringParameter A string.
	 * @author Marcus Povey
	 */
	class XMLRPCStringParameter extends XMLRPCParameter
	{
		function __construct($value)
		{
			parent::__construct();
			
			$this->value = $value; 
		}
		
		function __toString() 
		{
			$value = htmlentities($this->value);
			return "<value><string>{$value}</string></value>\n";
		}
	}
	
	/**
	 * @class XMLRPCDoubleParameter A double precision signed floating point number.
	 * @author Marcus Povey
	 */
	class XMLRPCDoubleParameter extends XMLRPCParameter
	{
		function __construct($value)
		{
			parent::__construct();
			
			$this->value = (float)$value; 
		}
		
		function __toString() 
		{
			return "<value><double>{$this->value}</double></value>\n";
		}
	}
	
	/**
	 * @class XMLRPCDateParameter An ISO8601 data and time.
	 * @author Marcus Povey
	 */
	class XMLRPCDateParameter extends XMLRPCParameter
	{
		/**
		 * Construct a date
		 *
		 * @param int $timestamp The unix timestamp, or blank for "now".
		 */
		function __construct($timestamp = 0)
		{
			parent::__construct();
			
			$this->value = $timestamp;
			if (!$timestamp)
				$this->value = time(); 
		}
		
		function __toString() 
		{
			$value = date('c', $this->value);
			return "<value><dateTime.iso8601>{$value}</dateTime.iso8601></value>\n";
		}
	}
	
	/**
	 * @class XMLRPCBase64Parameter A base 64 encoded blob of binary.
	 * @author Marcus Povey
	 */
	class XMLRPCBase64Parameter extends XMLRPCParameter
	{
		/**
		 * Construct a base64 encoded block
		 *
		 * @param string $blob Unencoded binary blob
		 */
		function __construct($blob)
		{
			parent::__construct();
			
			$this->value = base64_encode($blob);
		}
		
		function __toString() 
		{
			return "<value><base64>{$value}</base64></value>\n";
		}
	}
	
	/**
	 * @class XMLRPCStructParameter A structure containing other XMLRPCParameter objects.
	 * @author Marcus Povey
	 */
	class XMLRPCStructParameter extends XMLRPCParameter
	{
		/**
		 * Construct a struct.
		 *
		 * @param array $parameters Optional associated array of parameters, if not provided then addField must be used.
		 */
		function __construct($parameters = NULL)
		{
			parent::__construct();
			
			if (is_array($parameters))
			{
				foreach ($parameters as $k => $v)
					$this->addField($k, $v);
			}
		}
		
		/**
		 * Add a field to the container.
		 *
		 * @param string $name The name of the field.
		 * @param XMLRPCParameter $value The value.
		 */
		public function addField($name, XMLRPCParameter $value)
		{
			if (!is_array($this->value))
				$this->value = array();
				
			$this->value[$name] = $value;
		}
		
		function __toString() 
		{
			$params = "";
			foreach ($this->value as $k => $v)
			{
				$params .= "<member><name>$k</name>$v</member>";
			}
			
			return <<< END
<struct>
	$params
</struct>
END;
		}
	}
	
	/**
	 * @class XMLRPCArrayParameter An array containing other XMLRPCParameter objects.
	 * @author Marcus Povey
	 */
	class XMLRPCArrayParameter extends XMLRPCParameter
	{
		/**
		 * Construct an array.
		 *
		 * @param array $parameters Optional array of parameters, if not provided then addField must be used.
		 */
		function __construct($parameters = NULL)
		{
			parent::__construct();
			
			if (is_array($parameters))
			{
				foreach ($parameters as $v)
					$this->addField($v);
			}
		}
		
		/**
		 * Add a field to the container.
		 *
		 * @param XMLRPCParameter $value The value.
		 */
		public function addField(XMLRPCParameter $value)
		{
			if (!is_array($this->value))
				$this->value = array();
				
			$this->value[] = $value;
		}
		
		function __toString() 
		{
			$params = "";
			foreach ($this->value as $value)
			{
				$params .= "$value";
			}
			
			return <<< END
<array>
	<data>
	$params
	</data>
</array>
END;
		}
	}
	
	/**
	 * @class XMLRPCResponse XML-RPC Response. 
	 * @author Marcus Povey
	 */
	abstract class XMLRPCResponse
	{
		/** An array of parameters */
		protected $parameters = array();
		
		/**
		 * Add a parameter here.
		 *
		 * @param XMLRPCParameter $param The parameter.
		 */
		public function addParameter(XMLRPCParameter $param)
		{
			if (!is_array($this->parameters))
				$this->parameters = array();
				
			$this->parameters[] = $param;
		}

		public function addInt($value) { $this->addParameter(new XMLRPCIntParameter($value)); }
		public function addString($value) { $this->addParameter(new XMLRPCStringParameter($value)); }
		public function addDouble($value) { $this->addParameter(new XMLRPCDoubleParameter($value)); }
		public function addBoolean($value) { $this->addParameter(new XMLRPCBoolParameter($value)); }
	}

	/**
	 * @class XMLRPCSuccessResponse
	 * @author Marcus Povey
	 */
	class XMLRPCSuccessResponse extends XMLRPCResponse
	{
		/**
		 * Output to XML.
		 */
		public function __toString()
		{
			$params = "";
			foreach ($this->parameters as $param)
				$params .= "<param>$param</param>\n";
			
			return <<< END
<methodResponse>
	<params>
		$params
	</params>
</methodResponse>
END;
		}
	}

	/**
	 * @class XMLRPCErrorResponse
	 * @author Marcus Povey
	 */
	class XMLRPCErrorResponse extends XMLRPCResponse
	{		
		/**
		 * Set the error response and error code.
		 *
		 * @param string $message The message
		 * @param int $code Error code (default = system error as defined by http://xmlrpc-epi.sourceforge.net/specs/rfc.fault_codes.php)
		 */
		function __construct($message, $code = -32400)
		{
			$this->addParameter(
				new XMLRPCStructParameter(
					array (
						'faultCode' => new XMLRPCIntParameter($code),
						'faultString' => new XMLRPCStringParameter($message)
					)
				)
			);
		}
		
		/**
		 * Output to XML.
		 */
		public function __toString()
		{
			return <<< END
<methodResponse>
	<fault>
		<value>
			{$this->parameters[0]}
		</value>
	</fault>
</methodResponse>
END;
		}
	}
	
	// Functions for adding handlers //////////////////////////////////////////////////////////

	/** XML-RPC Handlers */
	$XML_RPC_HANDLERS = array();
	
	/**
	 * Register a method handler for a given XML-RPC method.
	 *
	 * @param string $method Method parameter.
	 * @param string $handler The handler function. This function accepts once XMLRPCCall object and must return a XMLRPCResponse object.
	 * @return bool
	 */
	function register_xmlrpc_handler($method, $handler)
	{
		global $XML_RPC_HANDLERS;
		
		$XML_RPC_HANDLERS[$method] = $handler;
	}
	
	/**
	 * Trigger a method call and pass the relevant parameters to the funciton.
	 *
	 * @param XMLRPCCall $parameters The call and parameters.
	 * @return XMLRPCCall
	 */
	function trigger_xmlrpc_handler(XMLRPCCall $parameters)
	{
		global $XML_RPC_HANDLERS;
		
		// Go through and see if we have a handler
		if (isset($XML_RPC_HANDLERS[$parameters->getMethodName()]))
		{
		    $handler = $XML_RPC_HANDLERS[$parameters->getMethodName()];
			$result  = $handler($parameters);
			
			if (!($result instanceof XMLRPCResponse))
				throw new InvalidParameterException(elgg_echo('InvalidParameterException:UnexpectedReturnFormat'));
				
			// Result in right format, return it.
			return $result;
		}
		
		// if no handler then throw exception
		throw new NotImplementedException(sprintf(elgg_echo('NotImplementedException:XMLRPCMethodNotImplemented'), $method));
	}
	
	// Error handler functions ////////////////////////////////////////////////////////////////

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
	function __php_xmlrpc_error_handler($errno, $errmsg, $filename, $linenum, $vars)
	{
		$error = date("Y-m-d H:i:s (T)") . ": \"" . $errmsg . "\" in file " . $filename . " (line " . $linenum . ")";
		
		switch ($errno) {
			case E_USER_ERROR:
					error_log("ERROR: " . $error);
					
					// Since this is a fatal error, we want to stop any further execution but do so gracefully.
					throw new Exception("ERROR: " . $error); 
				break;
	
			case E_WARNING :
			case E_USER_WARNING : 
					error_log("WARNING: " . $error);
				break;
	
			default:
				error_log("DEBUG: " . $error); 
		}
	}
	
	/**
	 * PHP Exception handler for XMLRPC.
	 * @param Exception $exception
	 */
	function __php_xmlrpc_exception_handler($exception) {
		
		error_log("*** FATAL EXCEPTION (XML-RPC) *** : " . $exception);
			
		page_draw($exception->getMessage(), elgg_view("xml-rpc/output", array('result' => new XMLRPCErrorResponse($exception->getMessage(), $exception->getCode()==0 ? -32400 : $exception->getCode()))));
	}
?>