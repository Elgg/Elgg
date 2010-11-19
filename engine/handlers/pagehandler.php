<?php
/**
 * Pages handler.
 *
 * This file dispatches pages.  It is called via a URL rewrite in .htaccess
 * from http://site/pg/handler/page1/page2.  The first element after 'pg/' is
 * the page handler name as registered by {@link register_page_handler()}.
 * The rest of the string is sent to {@link page_handler()}.
 *
 * {@link page_handler()} explodes the pages string by / and sends it to
 * the page handler function as registered by {@link register_page_handler()}.
 * If a valid page handler isn't found, the user will be forwarded to the site
 * front page.
 *
 * @package Elgg.Core
 * @subpackage PageHandler
 * @link http://docs.elgg.org/Tutorials/PageHandlers
 */

require_once(dirname(dirname(__FILE__)) . "/start.php");

$handler = get_input('handler');
$page = get_input('page');

if (!page_handler($handler, $page)) {
	forward();
}