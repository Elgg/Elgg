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

	require_once dirname(dirname(__FILE__)).'/classes/XMLRPCCall.php';

	
	// Response classes ///////////////////////////////////////////////////////////////////////

	require_once dirname(dirname(__FILE__)).'/classes/XMLRPCParameter.php';
	require_once dirname(dirname(__FILE__)).'/classes/XMLRPCIntParameter.php';
	require_once dirname(dirname(__FILE__)).'/classes/XMLRPCBoolParameter.php';
	require_once dirname(dirname(__FILE__)).'/classes/XMLRPCStringParameter.php';
	require_once dirname(dirname(__FILE__)).'/classes/XMLRPCDoubleParameter.php';
	require_once dirname(dirname(__FILE__)).'/classes/XMLRPCDateParameter.php';
	require_once dirname(dirname(__FILE__)).'/classes/XMLRPCBase64Parameter.php';
	require_once dirname(dirname(__FILE__)).'/classes/XMLRPCStructParameter.php';
	require_once dirname(dirname(__FILE__)).'/classes/XMLRPCArrayParameter.php';
	require_once dirname(dirname(__FILE__)).'/classes/XMLRPCResponse.php';
	require_once dirname(dirname(__FILE__)).'/classes/XMLRPCSuccessResponse.php';
	require_once dirname(dirname(__FILE__)).'/classes/XMLRPCErrorResponse.php';
	
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
	            // @todo unsupported, throw an error
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
