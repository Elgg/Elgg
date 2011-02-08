<?php
/**
 * XML-RPC handler.
 *
 * @warning This is very old code. Does it work at all?
 *
 * @package Elgg.Core
 * @subpackage XMLRPC
 * @link http://docs.elgg.org/Tutorials/XMLRPC
 * @todo Does this work?
 */

require_once(dirname(dirname(__FILE__)) . "/start.php");

// Register the error handler
error_reporting(E_ALL);
set_error_handler('_php_xmlrpc_error_handler');

// Register a default exception handler
set_exception_handler('_php_xmlrpc_exception_handler');

// Set some defaults
$result = null;
set_input('view', 'xml'); // Set default view regardless

// Get the post data
$input = get_post_data();

if ($input) {
	// 	Parse structures from xml
	$call = new XMLRPCCall($input);

	// Process call
	$result = trigger_xmlrpc_handler($call);
} else {
	throw new CallException(elgg_echo('xmlrpc:noinputdata'));
}

if (!($result instanceof XMLRPCResponse)) {
	throw new APIException(elgg_echo('APIException:ApiResultUnknown'));
}

// Output result
echo elgg_view_page("XML-RPC", elgg_view("xml-rpc/output", array('result' => $result)));
