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
 *  * mt
 *  * xml-rpc.php
 *  * rewrite.php
 *  * tag (deprecated, reserved for backwards compatibility)
 *  * pg (deprecated, reserved for backwards compatibility)
 *
 * {@link page_handler()} explodes the pages string by / and sends it to
 * the page handler function as registered by {@link elgg_register_page_handler()}.
 * If a valid page handler isn't found, plugins have a chance to provide a 404.
 *
 * @package Elgg.Core
 * @subpackage PageHandler
 * @link http://docs.elgg.org/Tutorials/PageHandlers
 */


// Permanent redirect to pg-less urls
$url = $_SERVER['REQUEST_URI'];
$new_url = preg_replace('#/pg/#', '/', $url, 1);

if ($url !== $new_url) {
	header("HTTP/1.1 301 Moved Permanently"); 
	header("Location: $new_url"); 
}

require_once(dirname(dirname(__FILE__)) . "/start.php");

$handler = get_input('handler');
$page = get_input('page');

if (!page_handler($handler, $page)) {
	forward('', '404');
}
