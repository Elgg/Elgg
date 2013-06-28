<?php
/**
 * Elgg front controller entry point
 *
 * @package Elgg
 * @subpackage Core
 */

require_once(dirname(__FILE__) . "/engine/start.php");

$router = _elgg_services()->router;
$request = _elgg_services()->request;

if (!$router->route($request)) {
	forward('', '404');
}
