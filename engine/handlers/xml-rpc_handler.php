<?php
/**
 * Elgg XML-RPC handler.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

// Load Elgg engine
define('externalpage',true);
require_once("../start.php");
global $CONFIG;

// Register the error handler
error_reporting(E_ALL);
set_error_handler('__php_xmlrpc_error_handler');

// Register a default exception handler
set_exception_handler('__php_xmlrpc_exception_handler');

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
page_draw("XML-RPC", elgg_view("xml-rpc/output", array('result' => $result)));