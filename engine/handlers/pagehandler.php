<?php
/**
 * Elgg page handler
 *
 * If page_handler() fails, send to front page.
 *
 * @package Elgg
 * @subpackage Core
 */

// Load Elgg engine
// define('externalpage',true);
require_once("../start.php");

// Get input
$handler = get_input('handler');
$page = get_input('page');

// Call the page handler functions
if (!page_handler($handler, $page)) {
	forward();
}