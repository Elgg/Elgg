<?php
/**
 * Elgg install script
 *
 * @package Elgg
 * @subpackage Core
 */

if (version_compare(PHP_VERSION, '5.5.0', '<')) {
	echo "Your server's version of PHP (" . PHP_VERSION . ") is too old to run Elgg.\n";
	exit;
}

$autoloader = require_once(__DIR__ . '/autoloader.php');

$installer = new ElggInstaller();

$step = get_input('step', 'welcome');
$installer->run($step);
