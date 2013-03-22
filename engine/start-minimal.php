<?php
/**
 * Minimal bootstrap for the Elgg engine.
 *
 * This file loads only sets up autoloading, for use in scripts to fetch configuration data, serve files
 * out of the dataroot, etc.
 *
 * If you need hook, events, plugins, and entity access you'll need to load start.php, which you can always
 * do after loading this.
 *
 * @access private
 *
 * @package Elgg.Core
 * @subpackage Core
 */

if (defined('ELGG_MINIMAL_BOOT_START_TIME')) {
	return;
}

define('ELGG_MINIMAL_BOOT_START_TIME', microtime(true));
define('ELGG_MINIMAL_BOOT_ENGINE_DIR', dirname(__FILE__));

global $CONFIG;
if (!isset($CONFIG)) {
	$CONFIG = new stdClass;
}

if (!include_once(ELGG_MINIMAL_BOOT_ENGINE_DIR . "/settings.php")) {
	header("Location: install.php");
	exit;
}

require ELGG_MINIMAL_BOOT_ENGINE_DIR . '/classes/Elgg/ClassMap.php';
require ELGG_MINIMAL_BOOT_ENGINE_DIR . '/classes/Elgg/ClassLoader.php';

$_elgg_autoloader = new Elgg_ClassLoader(new Elgg_ClassMap());
$_elgg_autoloader->addFallback(ELGG_MINIMAL_BOOT_ENGINE_DIR . '/classes');
$_elgg_autoloader->register();
unset($_elgg_autoloader);

/**
 * @return Elgg_MinimalBoot_Api
 */
function _elgg_minimal_boot_api() {
	static $inst;
	if (!$inst) {
		global $CONFIG;
		$inst = new Elgg_MinimalBoot_Api($CONFIG, ELGG_MINIMAL_BOOT_ENGINE_DIR);
	}
	return $inst;
}
