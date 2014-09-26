<?php
/**
 * Elgg front controller entry point
 *
 * @package Elgg
 * @subpackage Core
 */

// allow testing from the upgrade page before the site is upgraded.
if (isset($_GET['__testing_rewrite'])) {
	if (isset($_GET['__elgg_uri']) && false !== strpos($_GET['__elgg_uri'], '__testing_rewrite')) {
		echo "success";
	}
	exit;
}

require_once(dirname(__FILE__) . "/engine/start.php");

$router = _elgg_services()->router;
$request = _elgg_services()->request;

if (!$router->route($request)) {
	forward('', '404');
}