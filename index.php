<?php
/**
 * Elgg front controller entry point
 *
 * @package Elgg
 * @subpackage Core
 */

/*
 * Rewrite rules for PHP cli webserver used for testing. Do not use on production sites
 * as normal web server replacement.
 *
 * You need to explicitly point to index.php in order for router to work properly:
 *
 * <code>php -S localhost:8888 index.php</code>
 */
if (php_sapi_name() === 'cli-server') {
	$urlPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

	if (preg_match('/^\/cache\/(.*)$/', $urlPath, $matches)) {
		$_GET['request'] = $matches[1];
		require('engine/handlers/cache_handler.php');
		exit;
	} else if (preg_match('/^\/export\/([A-Za-z]+)\/([0-9]+)\/?$/', $urlPath, $matches)) {
		$_GET['view'] = $matches[1];
		$_GET['guid'] = $matches[2];
		require('engine/handlers/export_handler.php');
		exit;
	} else if (preg_match('/^\/export\/([A-Za-z]+)\/([0-9]+)\/([A-Za-z]+)\/([A-Za-z0-9\_]+)\/$/', $urlPath, $matches)) {
		$_GET['view'] = $matches[1];
		$_GET['guid'] = $matches[2];
		$_GET['type'] = $matches[3];
		$_GET['idname'] = $matches[4];
		require('engine/handlers/export_handler.php');
		exit;
	} else if (preg_match("/^\/rewrite.php$/", $urlPath, $matches)) {
		require('install.php');
		exit;
	} else if ($urlPath !== '/' && file_exists(__DIR__ . $urlPath)) {
		// serve the requested resource as-is.
		return false;
	} else {
		$_GET['__elgg_uri'] = $urlPath;
	}
}

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