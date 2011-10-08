<?php
/**
 * Elgg XML-RPC library.
 * Contains functions and classes to handle XML-RPC services, currently only server only.
 *
 * @package Elgg.Core
 * @subpackage XMLRPC
 */

/**
 * parse XMLRPCCall parameters
 *
 * Convert an XMLRPCCall result array into native data types
 *
 * @param array $parameters An array of params
 *
 * @return array
 * @access private
 */
function xmlrpc_parse_params($parameters) {
	$result = array();

	foreach ($parameters as $parameter) {
		$result[] = xmlrpc_scalar_value($parameter);
	}

	return $result;
}

/**
 * Extract the scalar value of an XMLObject type result array
 *
 * @param XMLObject $object And object
 *
 * @return mixed
 * @access private
 */
function xmlrpc_scalar_value($object) {
	if ($object->name == 'param') {
		$object = $object->children[0]->children[0];
	}

	switch ($object->name) {
		case 'string':
			return $object->content;

		case 'array':
			foreach ($object->children[0]->children as $child) {
				$value[] = xmlrpc_scalar_value($child);
			}
			return $value;

		case 'struct':
			foreach ($object->children as $child) {
				if (isset($child->children[1]->children[0])) {
					$value[$child->children[0]->content] = xmlrpc_scalar_value($child->children[1]->children[0]);
				} else {
					$value[$child->children[0]->content] = $child->children[1]->content;
				}
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
global $XML_RPC_HANDLERS;
$XML_RPC_HANDLERS = array();

/**
 * Register a method handler for a given XML-RPC method.
 *
 * @param string $method  Method parameter.
 * @param string $handler The handler function. This function accepts
 *                        one XMLRPCCall object and must return a XMLRPCResponse object.
 *
 * @return bool
 */
function register_xmlrpc_handler($method, $handler) {
	global $XML_RPC_HANDLERS;

	$XML_RPC_HANDLERS[$method] = $handler;
}

/**
 * Trigger a method call and pass the relevant parameters to the funciton.
 *
 * @param XMLRPCCall $parameters The call and parameters.
 *
 * @return XMLRPCCall
 * @access private
 */
function trigger_xmlrpc_handler(XMLRPCCall $parameters) {
	global $XML_RPC_HANDLERS;

	// Go through and see if we have a handler
	if (isset($XML_RPC_HANDLERS[$parameters->getMethodName()])) {
		$handler = $XML_RPC_HANDLERS[$parameters->getMethodName()];
		$result  = $handler($parameters);

		if (!($result instanceof XMLRPCResponse)) {
			$msg = elgg_echo('InvalidParameterException:UnexpectedReturnFormat',
				array($parameters->getMethodName()));
			throw new InvalidParameterException($msg);
		}

		// Result in right format, return it.
		return $result;
	}

	// if no handler then throw exception
	$msg = elgg_echo('NotImplementedException:XMLRPCMethodNotImplemented',
		array($parameters->getMethodName()));
	throw new NotImplementedException($msg);
}

/**
 * PHP Error handler function.
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
 */
function _php_xmlrpc_error_handler($errno, $errmsg, $filename, $linenum, $vars) {
	$error = date("Y-m-d H:i:s (T)") . ": \"" . $errmsg . "\" in file "
		. $filename . " (line " . $linenum . ")";

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
 *
 * @param Exception $exception The exception
 *
 * @return void
 * @access private
 */
function _php_xmlrpc_exception_handler($exception) {

	error_log("*** FATAL EXCEPTION (XML-RPC) *** : " . $exception);

	$code = $exception->getCode();

	if ($code == 0) {
		$code = -32400;
	}

	$result = new XMLRPCErrorResponse($exception->getMessage(), $code);

	$vars = array('result' => $result);

	$content = elgg_view("xml-rpc/output", $vars);

	echo elgg_view_page($exception->getMessage(), $content);
}
