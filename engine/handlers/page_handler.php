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
 */

require_once(dirname(dirname(__FILE__)) . "/start.php");

register_error("Update your .htaccess file to remove the page handler");

$router = _elgg_services()->router;
$request = _elgg_services()->request;

if (!$router->route($request)) {
	forward('', '404');
}
