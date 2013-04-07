<?php
/**
 * Pages handler.
 *
 * This file dispatches pages.  It is called via a URL rewrite in .htaccess
 * from http://site/handler/page1/page2.  The first element after site/ is
 * the page handler name as registered by {@link elgg_register_page_handler()}.
 * The rest of the string is sent to {@link page_handler()}.
 *
 * Note that the following handler names are reserved by elgg and should not be
 * registered by any plugins:
 *  * action
 *  * cache
 *  * services
 *  * export
 *  * js
 *  * css
 *  * rewrite.php
 *  * tag (deprecated, reserved for backwards compatibility)
 *  * pg (deprecated, reserved for backwards compatibility)
 *
 * These additionally are reserved for the xml-rpc plugin
 *  * mt
 *  * xml-rpc.php
 *
 * {@link page_handler()} explodes the pages string by / and sends it to
 * the page handler function as registered by {@link elgg_register_page_handler()}.
 * If a valid page handler isn't found, plugins have a chance to provide a 404.
 *
 * @package Elgg.Core
 * @subpackage PageHandler
 * @link http://docs.elgg.org/Tutorials/PageHandlers
 */

require_once(dirname(dirname(__FILE__)) . "/start.php");

// Permanent redirect to pg-less urls
$uri = $_SERVER['REQUEST_URI'];
$site_path = parse_url(elgg_get_site_url(), PHP_URL_PATH);
$site_path_quoted = preg_quote($site_path, '#');
$new_uri = preg_replace("#^{$site_path_quoted}pg/#", $site_path, $uri, 1);

if ($uri !== $new_uri) {
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: $new_uri");
	exit;
}

$router = _elgg_services()->router;
$request = _elgg_services()->request;

if (!$router->route($request)) {
	forward('', '404');
}
