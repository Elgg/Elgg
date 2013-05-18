<?php
/**
 * Elgg index page for web-based applications
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
