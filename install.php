<?php
/**
 * Elgg install script
 *
 * @package Elgg
 * @subpackage Core
 */

// check for PHP < 5.4 before we do anything else
if (version_compare(PHP_VERSION, '5.4.0', '<')) {
	echo "Your server's version of PHP (" . PHP_VERSION . ") is too old to run Elgg.\n";
	exit;
}

$autoload_path = __DIR__ . "/vendor/autoload.php";
$autoload_available = include_once($autoload_path);
if (!$autoload_available) {
	die("Couldn't include '$autoload_path'. Did you run `composer install`?");
}

$installer = new ElggInstaller();

$step = get_input('step', 'welcome');
$installer->run($step);
