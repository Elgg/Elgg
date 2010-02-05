<?php
	/**
	 * Elgg XML-RPC library.
	 * Contains functions and classes to handle XML-RPC services, currently only server only. 
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd
	 * @link http://elgg.org/
	 */

	// XMLRPC Call ////////////////////////////////////////////////////////////////////////////
	
	/**
	 * @class XMLRPCCall
	 * This class represents 
	 * @author Curverider Ltd
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
			$xml = xml_to_object($xml);
			
			// sanity check
			if ((isset($xml->name)) && (strcasecmp($xml->name, "methodCall")!=0))
				throw new CallException(elgg_echo('CallException:NotRPCCall'));
			
			// method name
			$this->methodname = $xml->children[0]->content;
			
			// parameters 
			$this->params = $xml->children[1]->children;			
		}
	}
	
	// Response classes ///////////////////////////////////////////////////////////////////////

	/**
	 * @class XMLRPCParameter Superclass for all RPC parameters.
	 * @author Curverider Ltd
	 */
	abstract class XMLRPCParameter
	{
		protected $value;

		function __construct() { }
			
	}
	
	/**
	 * @class XMLRPCIntParameter An Integer.
	 * @author Curverider Ltd
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
			return "<value><i4>{$this->value}</i4></value>";
		}
	}
	
	/**
	 * @class XMLRPCBoolParameter A boolean.
	 * @author Curverider Ltd
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
			return "<value><boolean>{$code}</boolean></value>";
		}
	}
	
	/**
	 * @class XMLRPCStringParameter A string.
	 * @author Curverider Ltd
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
			return "<value><string>{$value}</string></value>";
		}
	}
	
	/**
	 * @class XMLRPCDoubleParameter A double precision signed floating point number.
	 * @author Curverider Ltd
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
			return "<value><double>{$this->value}</double></value>";
		}
	}
	
	/**
	 * @class XMLRPCDateParameter An ISO8601 data and time.
	 * @author Curverider Ltd
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
			return "<value><dateTime.iso8601>{$value}</dateTime.iso8601></value>";
		}
	}
	
	/**
	 * @class XMLRPCBase64Parameter A base 64 encoded blob of binary.
	 * @author Curverider Ltd
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
			return "<value><base64>{$value}</base64></value>";
		}
	}
	
	/**
	 * @class XMLRPCStructParameter A structure containing other XMLRPCParameter objects.
	 * @author Curverider Ltd
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
			
			return "<value><struct>$params</struct></value>";
		}
	}
	
	/**
	 * @class XMLRPCArrayParameter An array containing other XMLRPCParameter objects.
	 * @author Curverider Ltd
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
			
			return "<array><data>$params</data></array>";
		}
	}
	
	/**
	 * @class XMLRPCResponse XML-RPC Response. 
	 * @author Curverider Ltd
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
	 * @author Curverider Ltd
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
			
			return "<methodResponse><params>$params</params></methodResponse>";
		}
	}

	/**
	 * @class XMLRPCErrorResponse
	 * @author Curverider Ltd
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
			return "<methodResponse><fault><value>{$this->parameters[0]}</value></fault></methodResponse>";
		}
	}
	
	
	// Helper functions ///////////////////////////////////////////////////////////////////////
	
	/**
	 * parse XMLRPCCall parameters
	 * 
	 * Convert an XMLRPCCall result array into native data types
	 *
	 * @param array $parameters
	 * @return array
	 */
	function xmlrpc_parse_params($parameters)
	{
	    $result = array();
	    
	    foreach ($parameters as $parameter)
	    {
	        $result[] = xmlrpc_scalar_value($parameter);
	    }
	    
	    return $result;
	}

	/**
	 * Extract the scalar value of an XMLObject type result array
	 *
	 * @param XMLObject $object
	 * @return mixed
	 */
	function xmlrpc_scalar_value($object)
	{
	    if ($object->name == 'param')
	    {
	        $object = $object->children[0]->children[0];
	    }
	
	    switch ($object->name)
	    {
	        case 'string':
	            return $object->content;
	        case 'array':
	            foreach ($object->children[0]->children as $child)
	            {
	                $value[] = xmlrpc_scalar_value($child);
	            }
	            return $value;
	        case 'struct':
	            foreach ($object->children as $child)
	            {
	            	if (isset($child->children[1]->children[0]))
	                	$value[$child->children[0]->content] = xmlrpc_scalar_value($child->children[1]->children[0]);
	                else
	                	$value[$child->children[0]->content] = $child->children[1]->content;
	            }
	            return $value;
	        case 'boolean':
	            return (boolean) $object->content;
	        case 'i4':
	        case 'int':
	            return (int) $object->content;
	        case 'double':
	            return (double) $object->content;
	        case 'dateTime.iso8601':
	            return (int) strtotime($object->content);
	        case 'base64':
	            return base64_decode($object->content);
	        case 'value':
	            return xmlrpc_scalar_value($object->children[0]);
	        default:
	            // TODO unsupported, throw an error
	            return false;
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
				throw new InvalidParameterException(sprintf(elgg_echo('InvalidParameterException:UnexpectedReturnFormat'), $parameters->getMethodName()));
				
			// Result in right format, return it.
			return $result;
		}
		
		// if no handler then throw exception
		throw new NotImplementedException(sprintf(elgg_echo('NotImplementedException:XMLRPCMethodNotImplemented'), $parameters->getMethodName()));
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
