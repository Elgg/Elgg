<?php
/**
 * Services handler.
 *
 * This file dispatches requests to web services.  It is called via a URL rewrite
 * in .htaccess from http://site/services/api/handler/response_format/request.
 * The first element after 'services/api/' is the service handler name as
 * registered by {@link register_service_handler()}.
 *
 * The remaining string is then passed to the {@link service_handler()}
 * which explodes by /, extracts the first element as the response format
 * (viewtype), and then passes the remaining array to the service handler
 * function registered by {@link register_service_handler()}.
 *
 * If a service handler isn't found, a 404 header is sent.
 *
 * @package Elgg.Core
 * @subpackage WebServices
 * @link http://docs.elgg.org/Tutorials/WebServices
 */

require_once(dirname(dirname(__FILE__)) . "/start.php");

$handler = get_input('handler');
$request = get_input('request');

service_handler($handler, $request);
