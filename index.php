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

require_once __DIR__ . '/autoloader.php';

$app = new \Elgg\Application();

return $app->run();
