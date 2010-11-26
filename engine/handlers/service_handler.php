<?php
/**
 * Elgg web services handler.
 *
 * @package Elgg
 * @subpackage Core
 */


// Load Elgg engine
define('externalpage',true);
require_once("../start.php");

// Get input
$handler = get_input('handler');
$request = get_input('request');

service_handler($handler, $request);