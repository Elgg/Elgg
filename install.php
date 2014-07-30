<?php
/**
 * Elgg install script
 *
 * @package Elgg
 * @subpackage Core
 */

// check for PHP 4 before we do anything else
if (version_compare(PHP_VERSION, '5.0.0', '<')) {
	echo "Your server's version of PHP (" . PHP_VERSION . ") is too old to run Elgg.\n";
	exit;
}

require_once __DIR__ . "/vendor/autoload.php";

$installer = new ElggInstaller();

$step = get_input('step', 'welcome');
$installer->run($step);
